<?php

namespace Nfq\Fairytale\CoreBundle\Util;

class Arrays
{
    /**
     * Returns $key element from each of $collection elements
     *
     * @param \Traversable $collection
     * @param string       $key
     * @return array
     */
    public static function pick($collection, $key)
    {
        $result = [];
        foreach ($collection as $item) {
            switch (true) {
                case (is_array($item)):
                    $result[] = $item[$key];
                    break;
                case (is_callable([$item, $key])):
                    $result[] = call_user_func([$item, $key]);
                    break;
                case (isset($item->$key)):
                    $result[] = $item->$key;
                    break;
                default:
                    $result[] = null;
            }
        }
        return $result;
    }

    public static function get($root, $path)
    {
        $keys = explode('.', $path);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($root[$key])) {
                $root[$key] = [];
            }
            $root = &$root[$key];
        }

        $key = reset($keys);
        return @$root[$key]; // hush hush
    }
} 
