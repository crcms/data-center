<?php

namespace CrCms\DataCenter\Commands;

use CrCms\DataCenter\DataContract;
use CrCms\DataCenter\Value;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class DataCenterCommand
 * @package CrCms\DataCenter\Commands
 */
class DataCenterCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:data {action : Method of execution. Supports the all get put flush delete method} {connection? : Default data connection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data center command operation';


    /**
     *
     */
    public function handle()
    {
        $action = $this->argument('action');
        $connnection = $this->argument('connection');
        if (is_null($connnection)) {
            $connnection = config('data-center.default');
        }

        $connnection = app('data-center.manager')->connection($connnection);
        $this->$action($connnection);
    }

    /**
     * @param DataContract $connnection
     */
    protected function flush(DataContract $connnection)
    {
        $connnection->flush();

        $this->info('The data flush success');
    }

    /**
     * @param DataContract $connnection
     */
    protected function put(DataContract $connnection)
    {
        $key = $this->ask('Please Input key');

        $value = $this->ask('Please Input value');

        $remark = $this->ask('Please Input remark');

        if ($this->confirm('Are you sure you want to put the value?', true)) {
            $connnection->put($key, $value, $remark);
            $this->info("The key:{$key} Already written");
        } else {
            $this->line('No value is written');
        }
    }

    /**
     * @param DataContract $connnection
     */
    protected function all(DataContract $connnection)
    {
        $this->table(['Key', 'Value'], collect($connnection->all())->map(function ($item, $key) {
            return ['key' => $key, 'value' => (new Value())->serialize($item)];
        })->toArray());
    }

    /**
     * @param DataContract $connnection
     */
    protected function get(DataContract $connnection)
    {
        $key = $this->ask('Please Input key');
        $value = $connnection->get($key);
        if (is_null($value)) {
            $this->error("The key:{$key} does not exist");
        }

        return $this->line((new Value())->serialize($value));
    }

    /**
     * @param DataContract $connnection
     */
    protected function delete(DataContract $connnection)
    {
        $key = $this->ask('Please Input key');

        if (!$connnection->has($key)) {
            $this->error("The key:{$key} does not exist");
        }

        $connnection->delete($key);

        $this->info("The key:{$key} delete success");
    }
}