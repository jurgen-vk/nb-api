<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Builder;
use AnthonyMartin\GeoLocation\GeoPoint;
use Mockery\Exception;

class Bear extends Model
{
    use HasFactory,HasSpatial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bear',
        'city',
        'province',
        'location'
    ];

    protected $casts = [
        'location' => Point::class,
    ];


    /**
     * @param Builder $query query get automatically passed by laravel
     *
     * @param object|point $location object|point containing the following properties:
     *
     *  - (int) srid: The srid used
     *
     *  - (float) latitude: the latitude of the location
     *
     *  - (float) longitude: the longtude of the location
     *
     * @param int $radius_in_km
     */
    public function scopeInRadius(Builder $query, $location, $radius_in_km)
    {

        //making a rough bounding box
        $geopoint = new geoPoint($location->latitude, $location->longitude);
        $box = $geopoint->boundingBox($radius_in_km, 'km');

        $query->select(
            "*",
            DB::raw("
                ROUND(
                    ST_DISTANCE_SPHERE(
                        `location`,
                        ST_GEOMFROMTEXT('
                            POINT(
                                {$location->latitude} {$location->longitude}
                            )'
                        )
                    ) / 1000, 3
                ) AS distance_in_km
            ")
        )->whereRaw('
            ST_CONTAINS(
                ST_MAKEENVELOPE(
                    ST_GEOMFROMTEXT(?),
                    ST_GEOMFROMTEXT(?)
                ),
                `location`
            )', [
            "POINT({$box->getMinLatitude()} {$box->getMinLongitude()})",
            "POINT( {$box->getMaxLatitude()} {$box->getMaxLongitude()})"
        ])->whereRaw('
                ST_DISTANCE_SPHERE(
                    `location`,
                    ST_GEOMFROMTEXT(?)
                ) <= ?', [
            "POINT({$location->latitude} {$location->longitude})",
            $radius_in_km * 1000 // Convert the distance to meters
        ])->orderBy('distance_in_km');
    }
}
