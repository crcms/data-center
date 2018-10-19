<?php

namespace CrCms\DataCenter\Drivers;

use Carbon\Carbon;
use CrCms\DataCenter\Value;
use CrCms\DataCenter\DataContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Arr;

/**
 * Class Database
 * @package CrCms\DataCenter\Drivers
 */
class Database implements DataContract
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var
     */
    protected $table;

    /**
     * @var Value
     */
    protected $value;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Database constructor.
     * @param ConnectionInterface $connection
     * @param Cache $cache
     * @param Value $value
     * @param array $config
     */
    public function __construct(ConnectionInterface $connection, Cache $cache, Value $value, array $config)
    {
        $this->connection = $connection;
        $this->cache = $cache;
        $this->value = $value;
        $this->config = $config;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $cacheKey = $this->cacheKey($key);
        $data = $this->cache->get($cacheKey);

        if (is_null($data)) {
            $data = $this->queryChannel()->where('key', '=', $key)->first();
            if (is_null($data)) {
                return $default;
            }
            $data = (array)$data;
        }

        return $this->value->unserialize($data['type'], $data['value']);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $has = $this->cache->has($this->cacheKey($key));

        return $has ? $has :
            (bool)$this->queryChannel()->where('key', '=', $key)->first();
    }

    /**
     * @param string $key
     * @param $value
     * @param string $remark
     * @return bool
     */
    public function put(string $key, $value, string $remark = ''): bool
    {
        $data = [
            'key' => $key,
            'channel' => $this->channel(),
            'type' => $this->value->type($value),
            'value' => $this->value->serialize($value),
            'remark' => $remark
        ];

        $result = $this->has($key) ?
            $this->queryChannel()->where('key', $key)->update(Arr::except($data, ['key', 'channel'])) :
            $this->query()->insert($data);

        $this->cache->put($this->cacheKey($key), $data, $this->getCacheRefreshTime());

        return $result !== false;
    }

    /**
     * @param string $key
     * @param null|string $app
     * @return bool
     */
    public function delete(string $key, ?string $app = null): bool
    {
        $this->cache->forget($this->cacheKey($key));

        return (bool)$this->queryChannel()->where('key', '=', $key)->delete();
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->queryChannel()->get()->each(function($item){
            $this->cache->forget($this->cacheKey($item->key));
            $this->delete($item->key);
        });
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->queryChannel()->get()->mapWithKeys(function ($item) {
            return [$item->key => $this->value->unserialize($item->type, $item->value)];
        })->toArray();
    }

    /**
     * @return string
     */
    protected function channel(): string
    {
        return $this->config['channel'] ?? 'default';
    }

    /**
     * @return Builder
     */
    protected function query(): Builder
    {
        return $this->connection->table($this->config['table']);
    }

    /**
     * @return Builder
     */
    protected function queryChannel(): Builder
    {
        return $this->connection->table($this->config['table'])->where('channel', $this->channel());
    }

    /**
     * @param string $key
     * @return string
     */
    protected function cacheKey(string $key): string
    {
        return "data_center_{$this->config['table']}_{$this->channel()}_{$key}";
    }

    /**
     * @return int
     */
    protected function getCacheRefreshTime(): int
    {
        return $this->config['refresh'] ?? 5;
    }
}