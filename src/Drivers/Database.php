<?php

namespace CrCms\AttributeContract\Connections;

use CrCms\AttributeContract\Contracts\ConnectionContract;
use CrCms\AttributeContract\Value;
use Illuminate\Database\ConnectionInterface;

/**
 * Class DatabaseConnection
 * @package CrCms\AttributeContract\Connections
 */
class Database implements ConnectionContract
{
    protected $connection;

    protected $table;

    protected $value;

    public function __construct(ConnectionInterface $connection, Value $value, string $table)
    {
        $this->connection = $connection;
        $this->value = $value;
        $this->table = $table;
    }

    public function get(string $key,?string $app = null)
    {
        $cache = $this->table()->where('key', '=', $key)->first();
        return $this->value->unserialize($cache->type, $cache->value);
    }

    public function exists(string $key,?string $app = null): bool
    {
        return (bool)$this->table()->where('key', '=', $prefixed)->first();
    }

    public function put(string $key, $value, string $remark = '', ?string $app = null): bool
    {
        $type = $this->value->type($type);
        $value = $this->value->serialize($value);
        return (bool)$this->table()->insert([
            'app' => $app, 'key' => $key, 'value' => $value, $remark
        ]);
    }

    public function remove(string $key,?string $app = null): bool
    {
        return (bool)$this->table()->where('key', '=', $key)->first();
    }

    public function all(string $key,?string $app = null)
    {
//        $this->table()->where('app')
    }


    protected function table()
    {
        return $this->connection->table($this->table);
    }
}