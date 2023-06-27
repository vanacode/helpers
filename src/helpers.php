<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

if (!function_exists('vn_dd_if')) {
    /**
     * Dump the passed variables and end the script based last condition argument.
     *
     * @param mixed
     * @return void
     */
    function vn_dd_if(...$args)
    {
        $condition = array_pop($args);
        if ($condition) {
            dd(...$args);
        };
    }
}

if (!function_exists('vn_if_dd')) {
    /**
     * Dump the passed variables and end the script based first condition argument.
     *
     * @param mixed
     * @return void
     */
    function vn_if_dd(...$args)
    {
        $condition = array_shift($args);
        if ($condition) {
            dd(...$args);
        };
    }
}

if (!function_exists('vn_dd_json')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed
     * @return void
     */
    function vn_dd_json(...$args)
    {
        dd(...$args);
    }
}

if (!function_exists('vn_dd_pluck')) {
    /**
     * @param Collection|array $collection
     * @param string $key
     * @param bool $toArray
     */
    function vn_dd_pluck(Collection|array $collection, string $key, bool $toArray = true)
    {
        if (is_array($collection)) {
            $collection = collect($collection);
        }
        $collection = $collection->pluck($key);
        $result = $toArray ? $collection->toArray() : $collection;
        dd($result);
    }
}

if (!function_exists('vn_dd_prev')) {
    /**
     *
     */
    function vn_dd_prev()
    {
        $info = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
        $object = $info['object'];
        $function = $info['function'];
        $args = $info['args'];
        dd($object->{$function}(...$args)); // @TODO
    }
}

if (!function_exists('vn_dd_keys')) {
    /**
     * @param array $data
     */
    function vn_dd_keys(array $data)
    {
        dd(array_keys($data));
    }
}

if (!function_exists('vn_if_dump')) {
    /**
     * Dump the passed variables and end the script based first condition argument.
     *
     * @param mixed
     * @return void
     */
    function vn_if_dump(...$args)
    {
        $condition = array_shift($args);
        if ($condition) {
            dump(...$args);
        };
    }
}

if (!function_exists('vn_dump_if')) {
    /**
     * Dump the passed variables and end the script based last condition argument.
     *
     * @param mixed
     * @return void
     */
    function vn_dump_if(...$args)
    {
        $condition = array_pop($args);
        if ($condition) {
            dump(...$args);
        };
    }
}

if (!function_exists('vn_show_bcrypt')) {
    /**
     * @param string $password
     */
    function vn_show_bcrypt(string $password)
    {
        dd(bcrypt($password));
    }
}

if (!function_exists('vn_show_class_methods')) {
    /**
     * @param object|string $class
     */
    function vn_show_class_methods(object|string $class)
    {
        $methods = get_class_methods($class);
        sort($methods);
        dd($methods);
    }
}

if (!function_exists('vn_show_class')) {
    /**
     * @param object $object
     */
    function vn_show_class(object $object)
    {
        dd(get_class($object));
    }
}

if (!function_exists('vn_show_query')) {
    /**
     * @param bool $isDie
     */
    function vn_show_query(bool $isDie = false)
    {
        $data = DB::getQueryLog();
        foreach ($data as $datum) {
            $bindings = $datum['bindings'];
            array_walk($bindings, function (&$value) {
                $value = is_string($value) ? '"' . $value . '"' : $value;
            });
            echo 'query: ' . sprintf(str_replace('?', '%s', $datum['query']), ...$bindings);
            echo '<br>';
            echo 'time: ' . ($datum['time'] / 1000) . ' sec';
            echo '<hr>';
        }
        if ($isDie) {
            die();
        }
    }
}

if (!function_exists('vn_show_object_methods')) {
    /**
     * @param object|string $object
     */
    function vn_show_object_methods(object|string $object)
    {
        vn_show_class_methods($object);
    }
}

if (!function_exists('vn_show_object_vars')) {
    /**
     * @param object $obj
     */
    function vn_show_object_vars(object $obj)
    {
        $vars = get_object_vars($obj);
        ksort($vars);
        dd($vars);
    }
}

if (!function_exists('vn_show_object_nested_vars')) {
    /**
     * @param object $obj
     * @param bool $isDoted
     */
    function vn_show_object_nested_vars(object $obj, bool $isDoted = true)
    {
        $vars = vn_get_object_vars_recursive($obj, $isDoted);
        dd($vars);
    }
}

if (!function_exists('vn_show_object_vars_methods')) {
    /**
     * @param object $object
     * @param bool $showObj
     */
    function vn_show_object_vars_methods(object $object, bool $showObj = false)
    {
        $vars = get_object_vars($object);
        ksort($vars);

        $methods = get_class_methods($object);
        sort($methods);

        if ($showObj) {
            $print = compact('object', 'vars', 'methods');
        } else {
            $print = compact('vars', 'methods');

        }
        dd($print);
    }
}

if (!function_exists('vn_get_object_vars_recursive')) {
    /**
     * @param object $object
     * @param bool $isDoted
     * @return array
     */
    function vn_get_object_vars_recursive(object $object, bool $isDoted = true)
    {
        $result = vn__get_object_vars_recursive($object);
        return $isDoted ? Arr::dot($result) : $result;
    }
}

if (!function_exists('vn__get_object_vars_recursive')) {
    /**
     * @param object $object
     * @return array
     */
    function vn__get_object_vars_recursive(object $object)
    {
        $result = [];
        $vars = get_object_vars($object);
        foreach ($vars as $var => $value) {
            if (is_object($value)) {
                $result[$var] = vn__get_object_vars_recursive($value); // @TODO
                continue;
            }

            if (!is_array($value)) {
                $result[$var] = $value;
                continue;
            }

            foreach ($value as $_value) {
                if (is_object($_value)) {
                    $result[$var][] = vn__get_object_vars_recursive($_value); // @TODO
                } else {
                    $result[$var] = $value;
                }
            }
        }

        return $result;
    }
}
