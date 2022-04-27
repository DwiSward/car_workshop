<?php

namespace App\Http\Controllers;

use App\Models\RepairServiceMechanic;
use App\Http\Requests\StoreRepairServiceMechanicRequest;
use App\Http\Requests\UpdateRepairServiceMechanicRequest;

class RepairServiceMechanicController extends Controller
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
     * @param  \App\Http\Requests\StoreRepairServiceMechanicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRepairServiceMechanicRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RepairServiceMechanic  $repairServiceMechanic
     * @return \Illuminate\Http\Response
     */
    public function show(RepairServiceMechanic $repairServiceMechanic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RepairServiceMechanic  $repairServiceMechanic
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairServiceMechanic $repairServiceMechanic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRepairServiceMechanicRequest  $request
     * @param  \App\Models\RepairServiceMechanic  $repairServiceMechanic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRepairServiceMechanicRequest $request, RepairServiceMechanic $repairServiceMechanic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RepairServiceMechanic  $repairServiceMechanic
     * @return \Illuminate\Http\Response
     */
    public function destroy(RepairServiceMechanic $repairServiceMechanic)
    {
        //
    }
}
