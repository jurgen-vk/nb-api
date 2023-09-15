<?php

if(!function_exists('are_filled')) {
    function are_filled(...$vars) {
        foreach($vars as $var) {
            if (empty($var)) {
                return false;
            }
        }

        return true;
    }
}

if(!function_exists('are_empty')) {
    function are_empty(...$vars) {
        foreach($vars as $var) {
            if (!empty($var)) {
                return false;
            }
        }

        return true;
    }
}
