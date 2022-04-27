<?php

namespace App\Http\Controllers\Api\Admins;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Helpers\ResponseHelper;
use App\Http\Requests\Api\Admins\StoreCarRequest;
use App\Http\Requests\Api\Admins\UpdateCarRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResponseHelper::responseFormat(true, 'Car Data', 200, Car::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $car = $this->saveData(new Car, $request);
        return ResponseHelper::responseFormat(true, 'Car Created', 200, $car);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return ResponseHelper::responseFormat(true, 'Car Data', 200, $car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCarRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, $id)
    {
        $car = $this->saveData(Car::findOrFail($id), $request);
        return ResponseHelper::responseFormat(true, 'Car Updated', 200, $car);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->save();
        return ResponseHelper::responseFormat(true, 'Car Deleted', 200, $car);
    }

    public function saveData($car, $request)
    {
        $car->brand = $request->brand;
        $car->color = $request->color;
        $car->license_plate = $request->license_plate;
        $car->type = $request->type;
        $car->customer_id = $request->customer_id;
        $car->save();
        return $car;
    }
}
