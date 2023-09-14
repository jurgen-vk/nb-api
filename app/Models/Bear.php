<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Builder;

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
    public function scopeInRadius(Builder $query, $location, $radius_in_km){
        $box = boundingBox(
            latitude: $location->latitude,
            longitude: $location->longitude,
            radius_in_km: $radius_in_km
        );

        $query->whereRaw('
            ST_CONTAINS(ST_MAKE_ENVELOPE(POINT(?, ?), POINT(?, ?)),location)', [
                $box->minLon,
                $box->minLat,
                $box->maxLon,
                $box->maxLat,
            ]
        );
    }
}
