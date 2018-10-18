<?php

namespace CrCms\DataCenter;

use CrCms\AttributeContract\Connections\DatabaseConnection;
use CrCms\DataCenter\Drivers\Database;
use CrCms\DataCenter\Drivers\File;
use DomainException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Application;

/**
 * Class Factory
 * @package CrCms\DataCenter
 */
class Factory
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Value
     */
    protected $value;

    /**
     * ConnectionFactory constructor.
     * @param Application $app
     * @param Value $value
     */
    public function __construct(Application $app, Value $value)
    {
        $this->app = $app;
        $this->value = $value;
    }

    public function factory(string $driver, array $config): DataContract
    {
        switch ($driver) {
            case 'database':
                return new Database(
                    $this->app->make(ConnectionInterface::class), $this->value, $config
                );
            case 'file':
                return new File($this->value, $config);
        }

        throw new DomainException("Driver {$driver} not exists");
    }
}