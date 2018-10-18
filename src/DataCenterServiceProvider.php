<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/07/02 19:15
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\DataCenter;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

/**
 * Class MicroServiceProvider
 * @package CrCms\Foundation\Rpc
 */
class DataCenterServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @var string
     */
    protected $namespaceName = 'data-center';

    /**
     * @var string
     */
    protected $packagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * @return void
     */
    public function boot()
    {
        //move config path
        if ($this->isLumen()) {

        } else {
            $this->loadMigrationsFrom($this->packagePath . '/database/migrations');

            $this->publishes([
                $this->packagePath . 'config/config.php' => config_path($this->namespaceName . ".php"),
            ]);
        }
    }

    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->isLumen()) {
            $this->app->configure($this->namespaceName);
        }

        //merge config
        $configFile = $this->packagePath . "config/config.php";
        if (file_exists($configFile)) $this->mergeConfigFrom($configFile, $this->namespaceName);

        $this->registerAlias();
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton('data-center.factory', function ($app) {
            return new Factory($app, new Value);
        });

        $this->app->singleton('data-center.manager', function ($app) {
            return new DataCenterManager($app, $this->app->make('attribute.factory'));
        });

        $this->app->singleton('cache.connection', function ($app) {
            return $app['data-center.manager']->connection();
        });
    }

    /**
     * @return bool
     */
    protected function isLumen(): bool
    {
        return class_exists(Application::class) && $this->app instanceof Application;
    }

    /**
     * @return void
     */
    protected function registerCommands(): void
    {
    }

    /**
     * @return void
     */
    protected function registerAlias(): void
    {
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
        ];
    }
}