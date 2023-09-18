<?php

namespace App\Http\Controllers;

use App\Models\Bear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Http\Traits\BearTrait;

class BearController extends Controller
{
    use BearTrait;

    // =====[ Single Bear ]===============
    // show, store, update, delete

    /**
     * Display a single bear.
     *
     * @param Bear bear
     * @return \Illuminate\Http\JsonResponse Bears
     */
    public function show(Bear $bear) : \Illuminate\Http\JsonResponse
    {
        apilog(Auth::id(), $bear->id, 'Read');

        return response()->json([
            'message' => 'Bear successfully located',
            'data' => $bear,
        ], 200);
    }

    /**
     * Validate and insert a single bear.
     *
     * @param Request $request Request
     * @throws ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : \Illuminate\Http\JsonResponse
    {
        $data = collect($request->json());
        $this->validateBear($data->all());
        $newBear = $this->insertBear($data);

        apilog(Auth::id(), $newBear->id, 'Create');

        return response()->json([
            'message' => 'Bear successfully created',
            'data' => $newBear,
        ], 201);
    }

    /**
     * Validate and update a single bear
     *
     * @param Bear $bear Bear
     * @param Request $request Request
     * @throws ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Bear $bear, Request $request) : \Illuminate\Http\JsonResponse
    {
        $data = collect($request->json());
        $this->validateBear($data->all());
        $changedBear = $this->changeBear($bear, $data);

        apilog(Auth::id(), $changedBear->id, 'Update');

        return response()->json([
            'message' => 'Bear successfully updated',
            'data' => $changedBear,
        ], 201);
    }

    /**
     * Delete a single bear.
     *
     * @param Bear $bear Bear
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Bear $bear)
    {
        $bear->delete();

        apilog(Auth::id(), $bear->id, 'Delete');

        return response()->json([
            'message' => 'Bear successfully deleted',
            'data' => $bear,
        ], 200); // chose 200 instead of 204 because i want to show which resource was deleted back to the client
    }




    // =====[ Multiple Bears ]===============
    // display, create, modify, remove

    /**
     * Display multiple bears.
     *
     * @param Request $request request
     * @return \Illuminate\Http\JsonResponse Bears
     */
    public function display(Request $request) : \Illuminate\Http\JsonResponse
    {
        // Filters
        $rad = $request->radius;
        $lat = $request->latitude;
        $lon = $request->longitude;

        if(are_filled($rad, $lat, $lon)){
            $location = new Point($lat, $lon);
            $bears = Bear::inRadius($location, $rad)->get();

            apilog(Auth::id(), null, 'ReadRadius');
        } else {
            $bears = Bear::all();

            apilog(Auth::id(), null, 'ReadAll');
        }

        return response()->json([
            'message' => 'Bears successfully located',
            'data' => $bears,
        ], 200);
    }

    /**
     * Validate and insert multiple bears.
     *
     * @param Request $request Request
     * @throws ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) : \Illuminate\Http\JsonResponse
    {
        $data = collect($request->json());

        // this is purposefully split into 2 foreach loops, so that if one of the records has an error, you don't accidentally insert partial data.
        foreach($data as $item) {
            $this->validateBear($item);
        }

        $newBears = [];
        foreach($data as $item) {
            $bear = $this->insertBear($item);
            $newBears[] = $bear;

            apilog(Auth::id(), $bear->id, 'Create');
        }

        return response()->json([
            'message' => 'Bears successfully created',
            'data' => $newBears,
        ], 201);
    }

    /**
     * Validate and update multiple bears
     *
     * @param Request $request Request
     * @throws ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request) : \Illuminate\Http\JsonResponse
    {
        $data = collect($request->json());
        foreach($data as $item) {
            $this->validateBear($item);
        }

        $changedBears = [];
        foreach($data as $item) {
            $bear = Bear::findOrFail($item['id']);
            $changedBears[] = $this->changeBear($bear, $item);

            apilog(Auth::id(), $bear->id, 'Update');
        }

        return response()->json([
            'message' => 'Bears successfully updated',
            'data' => $changedBears,
        ], 201);
    }

    /**
     * Delete multiple bears.
     *
     * @param array $ids array with ids of bears to delete
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request) : \Illuminate\Http\JsonResponse
    {
        $deletedBears = [];

        $data = collect($request->json());

        foreach($data->get('ids') as $id) {
            $bear = Bear::findOrFail($id);
            $deletedBears[] = $bear;
            $bear->delete();

            apilog(Auth::id(), $bear->id, 'Delete');
        }

        return response()->json([
            'message' => 'Bears successfully deleted',
            'data' => $deletedBears,
        ], 200); // chose 200 instead of 204 because i want to show which resource was deleted back to the client
    }
}
