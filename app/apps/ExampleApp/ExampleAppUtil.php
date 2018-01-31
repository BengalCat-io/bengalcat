<?php

/*
 * Example Util - Add any helper static methods you need here
 */

namespace Bc\App\Apps\ExampleApp;

use Bc\App\Core\Util;


class ExampleAppUtil {

    static public function formValue($expectedValue, $default = null)
    {
        if (!isset($expectedValue)) {
            return $default;
        }

        return $expectedValue;
    }

    static public function objectValue($key, $object, $default = null)
    {
        if (!isset($object->{$key})) {
            return $default;
        }

        return $object->{$key};
    }

    static public function allPropsSet(
        $object,
        $propsToCheck = [],
        $notEmptyToo = false,
        &$propsNotSet = []
    ) {
        $arr    = is_array($object)
                ? $object
                : Util::arrayifyObject($object);
        $object = is_array($object)
                ? Util::objectifyArray($object)
                : $object;
        $props  = empty($propsToCheck) ? array_keys($arr) : $propsToCheck;
        $answer = true;

        foreach ($props as $prop) {

            $isset = isset($object->{$prop});
            $notEmpty = $isset ? !empty($object->{$prop}) : false;

            if ($isset && $notEmpty) {
                continue; // it is set and not empty. it passes no matter what.
            }

            if ($isset && !$notEmptyToo) {
                continue; // it is set and is empty but we didnt mind. pass.
            }

            // It is either set and empty (and we are checking empty)
            // or it is not set.
            $propsNotSet[] = $prop;
            $answer = false;
        }

        return $answer;
    }

    static public function atLeastOnePropSet($object, $propsToCheck = [], $notEmptyToo = false)
    {
        $arr = Util::arrayifyObject($object);
        $props = empty($propsToCheck) ? array_keys($arr) : $propsToCheck;
        $answer = false;

        foreach ($props as $prop) {
            if (!$notEmptyToo && isset($object->$prop)) {
                $answer = true;
            }

            if ($notEmptyToo && !empty($object->$prop)) {
                $answer = true;
            }
        }

        return $answer;
    }

    static public function generatePasswordResetKey()
    {
        return sha1(microtime(true).random_int(10000,90000));
    }
    
    /**
     * Split name (only up to 3 words handled)
     * @param string $string The name to split
     * @param mixed $which false for returning name object; 'nameFirst' | 'nameMiddle' | 'nameLast' for specific.
     * @return mixed Object or false or string
     */
    static public function  splitName($string, $which = false)
    {
        $arr = explode(' ', $string);
        $num = count($arr);

        if ($num == 2) {
            list($nameFirst, $nameLast) = $arr;
        } else {
            list($nameFirst, $nameMiddle, $nameLast) = $arr;
        }
        
        if (empty($nameFirst) || $num > 3) {
            return false;
        }
        
        $names = (object) [
            'nameFirst' => $nameFirst,
            'nameMiddle' => $nameMiddle,
            'nameLast' => $nameLast,
        ];
        
        return ($which) ? (isset($names->$which) ? $names->$which : false) : $names;
    }
    
    static public function slugToCamel($str)
    {
        $pieces = explode('-', $str);
        $first  = strtolower(array_shift($pieces));
        
        $pieces = array_map('ucwords', $pieces);
        
        return $first . implode('', $pieces);
    }
    
    static public function camelToSlug($str)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $str));
    }
}
