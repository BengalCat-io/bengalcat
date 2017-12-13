<?php

if (!function_exists('getallheaders'))  {
    function getallheaders()
    {
        if (!is_array($_SERVER)) {
            return array();
        }

        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('var_print')) {
    function var_print($data = null) {
        \Bc\App\Core\Util::var_print($data);
    }
}

if (!function_exists('var_hprint')) {
    function var_hprint($data = null, $heading = '') {
        \Bc\App\Core\Util::var_hprint($data, $heading);
    }
}


if (!function_exists('debug_log')) {
    function debug_log($data = null, $message = '') {
        \Bc\App\Core\Util::debugLog($data, $message);
    }
}