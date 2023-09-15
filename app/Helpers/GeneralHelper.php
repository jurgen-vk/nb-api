<?php

if(!function_exists('are_filled')) {
    /**
     * Checks if all given variables are filled and truthy
     *
     * @param mixed ...$vars
     * @return bool return true if ALL variables are filled and truthy, else false
     */
    function are_filled(...$vars) : bool{
        foreach($vars as $var) {
            if (empty($var)) {
                return false;
            }
        }

        return true;
    }
}


if(!function_exists('are_empty')) {

    /**
     * Checks if all given variables are empty and/or falsy
     *
     * @param mixed ...$vars
     * @return bool return true if ALL variables are empty or falsy, else false
     */
    function are_empty(...$vars) : bool {
        foreach($vars as $var) {
            if (!empty($var)) {
                return false;
            }
        }

        return true;
    }
}


if(!function_exists("validate_location")) {
    /**
     * Validates a given coordinate
     *
     * @param float|int|string $latitude Latitude
     * @param float|int|string $longitude Longitude
     * @return bool `true` if the coordinates are valid, `false` if not
     */
    function validate_location($latitude, $longitude) : bool{
        $location = "$latitude, $longitude";
        return preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $location);
    }
}

