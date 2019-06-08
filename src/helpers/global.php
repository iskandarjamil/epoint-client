<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (! function_exists('dd')) {
    function dd($args)
    {
        dump($args);
        exit;
    }
}

if (! function_exists('dump')) {
    function dump()
    {
        echo '<pre>';
        var_dump(func_get_args());
        echo '</pre>';
    }
}
