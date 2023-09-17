<?php

namespace App\Http\Traits;

use App\Models\Bear;
use App\Rules\CollectionArrayRule;
use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use MatanYadaev\EloquentSpatial\Objects\Point;
use mysql_xdevapi\Collection;

Trait BearTrait {
    /**
     * Validate a bear.
     *
     * @param array $data single bear to be validated.
     * @return void
     *@throws ValidationException
     */
    public function validateBear(array $data) : void
    {
        //custom validation rules
        $latitude = new LatitudeRule();
        $longitude = new LongitudeRule();

        Validator::make($data, [
            'bear' => ['required', 'string', 'filled'],
            'city' => ['required', 'string', 'filled'],
            'province' => ['required', 'string', 'filled'],
            'location.latitude' => ['required', 'filled', $latitude],
            'location.longitude' => ['required', 'filled', $longitude],
        ])->validate();
    }

    /**
     * Save a bear to the database.
     *
     * @param \Illuminate\Support\Collection|array $data single bear to be inserted.
     * @return bool|\Illuminate\Http\JsonResponse Inserted bear.
     */
    public function insertBear(\Illuminate\Support\Collection|array $data) : Bear|\Illuminate\Http\JsonResponse {
        if(gettype($data) != 'object') {
            $data = collect($data);
        }

        $bear = new Bear;
        $bear->bear = $data->get('bear');
        $bear->city = $data->get('city');
        $bear->province = $data->get('province');
        $bear->location = new Point($data->grab('location.latitude'), $data->grab('location.longitude'));
        $bear->save();

        return $bear;
    }


    /**
     * Update a bear to the database.
     *
     * @param Bear $bear Bear that will be updated.
     * @return bool|\Illuminate\Http\JsonResponse Updated bear.
     */
    public function changeBear(Bear $bear, \Illuminate\Support\Collection|array $data) : Bear|\Illuminate\Http\JsonResponse {
        if(gettype($data) != 'object') {
            $data = collect($data);
        }

        $bear->bear = $data->get('bear');
        $bear->city = $data->get('city');
        $bear->province = $data->get('province');
        $bear->location = new Point($data->grab('location.latitude'), $data->grab('location.longitude'));
        $bear->save();

        return $bear;
    }
}
