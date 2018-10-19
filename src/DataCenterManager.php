<?php

namespace CrCms\DataCenter;

use Illuminate\Contracts\Container\Container;

/**
 * Class DataCenterManager
 * @package CrCms\DataCenter
 */
class DataCenterManager
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * DataCenterManager constructor.
     * @param Container $app
     * @param Factory $factory
     */
    public function __construct(Container $app, Factory $factory)
    {
        $this->app = $app;
        $this->factory = $factory;
    }

    /**
     * @param null|string $driver
     */
    public function makeConnection(string $driver)
    {
        return $this->factory->factory($driver, $this->configure($driver));
    }

    /**
     * @param null|string $driver
     * @return mixed
     */
    public function connection(?string $driver = null)
    {
        $driver = $driver ? $driver : $this->defaultDriver($driver);

        if (!isset($this->connections[$driver])) {
            $this->connections[$driver] = $this->makeConnection($driver);
        }

        return $this->connections[$driver];
    }

    /**
     * @param null|string $driver
     * @return mixed
     */
    protected function configure(string $driver)
    {
        return $this->app->make('config')->get("data-center.connections.{$driver}");
    }

    /**
     * @param null|string $driver
     * @return string
     */
    protected function defaultDriver(?string $driver = null): string
    {
        if (is_null($driver)) {
            $driver = $this->app->make('config')->get('data-center.default');
        }
        return $driver;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->connection()->$name(...$arguments);
    }
}