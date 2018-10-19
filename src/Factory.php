<?php

namespace CrCms\DataCenter;

use CrCms\AttributeContract\Connections\DatabaseConnection;
use CrCms\DataCenter\Drivers\Database;
use CrCms\DataCenter\Drivers\File;
use DomainException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;

/**
 * Class Factory
 * @package CrCms\DataCenter
 */
class Factory
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var Value
     */
    protected $value;

    /**
     * Factory constructor.
     * @param Container $app
     * @param Value $value
     */
    public function __construct(Container $app, Value $value)
    {
        $this->app = $app;
        $this->value = $value;
    }

    /**
     * @param string $driver
     * @param array $config
     * @return DataContract
     */
    public function factory(string $driver, array $config): DataContract
    {
        switch ($config['driver']) {
            case 'database':
                return new Database(
                    $this->app['db']->connection($config['connection'] ?? null),
                    $this->app['cache']->driver('file'),
                    $this->value, $config
                );
            case 'file':
                return new File($this->value, $config);
        }

        throw new DomainException("Driver {$config['driver']} not exists");
    }
}