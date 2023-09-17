<?php

use App\Models\ApiLog;
use App\Models\AuthLog;

if(!function_exists('are_filled')) {
    /**
     * Checks if all given variables are filled and truthy
     *
     * @param mixed ...$vars
     * @return bool return true if ALL variables are filled and truthy, else false
     */
    function are_filled(mixed ...$vars) : bool{
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
    function are_empty(mixed ...$vars) : bool {
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
    function validate_location(float|int|string  $latitude, float|int|string  $longitude) : bool{
        $location = "$latitude, $longitude";
        return preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $location);
    }
}


if(!function_exists("validate_latitude")) {
    /**
     * Validates a given latitude
     *
     * @param float|int|string $latitude Latitude
     * @return bool `true` if the coordinates are valid, `false` if not
     */
    function validate_latitude(float|int|string  $latitude) : bool{
        return preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/', $latitude);
    }
}


if(!function_exists("validate_longitude")) {
    /**
     * Validates a given longitude
     *
     * @param float|int|string $longitude Longitude
     * @return bool `true` if the coordinates are valid, `false` if not
     */
    function validate_longitude(float|int|string  $longitude) : bool{
        return preg_match('/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $longitude);
    }
}


if(!function_exists('array_grab')) {
    /**
     * Multidimesinal array search by a given key
     *
     * @param array array
     * @return bool `true` if the coordinates are valid, `false` if not
     */
    function array_grab($array, $search_key) {
        $keys = explode('.', $search_key);
        $array_keys = "";

        foreach($keys as $key) {
            $array_keys .= "['$key']";
        }

        $stmt = "\$array$array_keys";

        return eval("return $stmt;");

    }
}



if(!function_exists('apilog')) {
    /**
     * Makes new ApiLog
     *
     * @param int|string $user_id id of the user
     * @param int|string $bear_id id of the bear
     * @param string $action action that the user did (create, read, update, delete)
     * @return void
     */
    function apilog(int|string $user_id, int|string|null $bear_id, string $action) : void {

        $apilog = new ApiLog;
        $apilog->user_id = $user_id;
        $apilog->bear_id = $bear_id;
        $apilog->action = $action;
        $apilog->save();
    }
}
