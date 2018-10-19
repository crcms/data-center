<?php

namespace CrCms\DataCenter;

/**
 * Class Value
 * @package CrCms\DataCenter
 */
class Value
{
    /**
     *
     */
    const TYPE_STRING = 'string';

    /**
     *
     */
    const TYPE_INTEGER = 'integer';

    /**
     *
     */
    const TYPE_FLOAT = 'float';

    /**
     *
     */
    const TYPE_BOOL = 'bool';

    /**
     *
     */
    const TYPE_ARRAY = 'array';

    /**
     *
     */
    const TYPE_OBJECT = 'object';

    /**
     * @param $value
     * @param null|string $type
     * @return mixed
     */
    public function serialize($value, ?string $type = null)
    {
        $type = $type ? $type : $this->type($value);

        switch ($type) {
            case static::TYPE_OBJECT:
                return serialize($value);
            case static::TYPE_ARRAY:
                return json_encode($value);
            default:
                return $value;
        }
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    public function unserialize(string $type, $value)
    {
        switch ($type) {
            case static::TYPE_OBJECT:
                return unserialize($value);
            case static::TYPE_ARRAY:
                return json_decode($value, true);
            case static::TYPE_BOOL:
                return (bool)$value;
            case static::TYPE_INTEGER:
                return intval($value);
            case static::TYPE_FLOAT:
                return boolval($value);
            case static::TYPE_STRING:
                return strval($value);
            default:
                return (constant('static::TYPE_' . strtoupper($type)))($value);
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function type($value): string
    {
        switch ($value) {
            case is_array($value):
                return static::TYPE_ARRAY;
            case is_object($value):
                return static::TYPE_OBJECT;
            case is_integer($value):
                return static::TYPE_INTEGER;
            case is_float($value):
                return static::TYPE_FLOAT;
            case is_string($value):
                return static::TYPE_STRING;
            case is_bool($value):
                return static::TYPE_BOOL;
        }
    }
}