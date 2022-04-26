<?php

namespace App\Http\Controllers;

use App\Models\RepairService;
use App\Http\Requests\StoreRepairServiceRequest;
use App\Http\Requests\UpdateRepairServiceRequest;

class RepairServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRepairServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRepairServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RepairService  $repairService
     * @return \Illuminate\Http\Response
     */
    public function show(RepairService $repairService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RepairService  $repairService
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairService $repairService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRepairServiceRequest  $request
     * @param  \App\Models\RepairService  $repairService
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRepairServiceRequest $request, RepairService $repairService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RepairService  $repairService
     * @return \Illuminate\Http\Response
     */
    public function destroy(RepairService $repairService)
    {
        //
    }
}
