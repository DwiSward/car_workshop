<?php

namespace App\Http\Controllers\Api\Admins;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Helpers\ResponseHelper;
use App\Http\Requests\Api\Admins\StoreServiceRequest;
use App\Http\Requests\Api\Admins\UpdateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResponseHelper::responseFormat(true, 'Service Data', 200, Service::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        $service = $this->saveData(new Service, $request);
        return ResponseHelper::responseFormat(true, 'Service Created', 200, $service);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return ResponseHelper::responseFormat(true, 'Service Data', 200, ['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateServiceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        return ResponseHelper::responseFormat(true, 'Service Updated', 200, $service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return ResponseHelper::responseFormat(true, 'Service Deleted', 200, $service);
    }

    public function saveData($service, $request)
    {
        $service->name = $request->name;
        $service->price = $request->price;
        $service->save();
        return $service;
    }
}
