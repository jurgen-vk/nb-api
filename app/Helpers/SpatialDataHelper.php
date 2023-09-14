<?php

if(!function_exists('boundingBox')) {
    function boundingBox($latitude, $longitude, $radius_in_km)
    {
        $lat_limits = [deg2rad(-90), deg2rad(90)];
        $lon_limits = [deg2rad(-180), deg2rad(180)];

        $rad_lat = deg2rad($latitude);
        $rad_lon = deg2rad($longitude);

        if ($rad_lat < $lat_limits[0] || $rad_lat > $lat_limits[1]
            || $rad_lon < $lon_limits[0] || $rad_lon > $lon_limits[1]) {
            throw new \Exception("Invalid Argument");
        }

        $angular = $radius_in_km / 6371.01;

        $min_lat = $rad_lat - $angular;
        $max_lat = $rad_lat + $angular;

        if ($min_lat > $lat_limits[0] && $max_lat < $lat_limits[1]) {
            $delta_lon = asin(sin($angular) / cos($rad_lat));
            $min_lon = $rad_lon - $delta_lon;

            if ($min_lon < $lon_limits[0]) {
                $min_lon += 2 * pi();
            }

            $max_lon = $rad_lon + $delta_lon;

            if ($max_lon > $lon_limits[1]) {
                $max_lon -= 2 * pi();
            }
        } else {
            // A pole is contained within the distance.
            $min_lat = max($min_lat, $lat_limits[0]);
            $max_lat = min($max_lat, $lat_limits[1]);
            $min_lon = $lon_limits[0];
            $max_lon = $lon_limits[1];
        }

        return (object) [
            'minLat' => rad2deg($min_lat),
            'minLon' => rad2deg($min_lon),
            'maxLat' => rad2deg($max_lat),
            'maxLon' => rad2deg($max_lon),
        ];
    }
}
