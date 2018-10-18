<?php

namespace CrCms\DataCenter;

use Illuminate\Support\Collection;

/**
 * Interface DataContract
 * @package CrCms\DataCenter
 */
interface DataContract
{
    public function has(string $key): bool;

    public function get(string $key);

    public function put(string $key, $value): bool;

    public function delete(string $key): bool;

    public function all(string $key);
}