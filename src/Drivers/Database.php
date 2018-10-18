<?php

namespace CrCms\DataCenter\Drivers;

use CrCms\AttributeContract\Contracts\ConnectionContract;
use CrCms\AttributeContract\Value;
use CrCms\DataCenter\DataContract;
use Illuminate\Database\ConnectionInterface;

/**
 * Class Database
 * @package CrCms\DataCenter\Drivers
 */
class Database implements DataContract
{
    protected $connection;

    protected $table;

    protected $value;

    protected $config;

    public function __construct(ConnectionInterface $connection, Value $value, array $config)
    {
        $this->connection = $connection;
        $this->value = $value;
        $this->config = $config;
    }

    public function get(string $key)
    {
        $cache = $this->query()->where('key', '=', $key)->first();
        return $this->value->unserialize($cache->type, $cache->value);
    }

    public function has(string $key): bool
    {
        return (bool)$this->query()->where('key', '=', $prefixed)->first();
    }

    public function put(string $key, $value, string $remark = ''): bool
    {
//        $type = $this->value->type($type);
        $value = $this->value->serialize($value);
        return (bool)$this->query()->insert([
            'channel' => $this->channel(), 'key' => $key, 'value' => $value, $remark
        ]);
    }

    public function delete(string $key, ?string $app = null): bool
    {
        return (bool)$this->query()->where('key', '=', $key)->first();
    }

    public function all(string $key, ?string $app = null)
    {
//        $this->table()->where('app')
    }

    protected function channel(): string
    {
        return $this->config['channel'] ?? 'default';
    }

    protected function query()
    {
        return $this->connection->table($this->config['table'])->where('channel',$this->channel());
    }
}