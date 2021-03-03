<?php

namespace app\core\utilities;

class ArrayUtils
{
    public static function isAssoc(Array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function changeKey($array, $oldKey, $newKey)
    {
        if(!array_key_exists($oldKey, $array)) return $array;
        $keys = array_keys($array);
        $keys[array_search($oldKey, $keys)] = $newKey;
        return array_combine($keys, $array);
    }

    public static function getAllKeyValuePairsFromNestedArray(array $arr, array $flatArray = null)
    {
        $flatArray = $flatArray ?? [];
        foreach ($arr as $key => &$value)  {
            $flatArray[] = [$key, $value];
            if (is_array($value)) {
                $flatArray = self::getAllKeyValuePairsFromNestedArray($value, $flatArray);
            }
        }
        return $flatArray;
    }
}