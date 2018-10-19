<?php

namespace CrCms\DataCenter;

use Illuminate\Support\Collection;

/**
 * Interface DataContract
 * @package CrCms\DataCenter
 */
interface DataContract
{
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    public function put(string $key, $value): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return void
     */
    public function flush(): void;
}