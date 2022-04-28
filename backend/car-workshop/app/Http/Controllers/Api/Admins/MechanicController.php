<?php

namespace App\Http\Controllers\Api\Admins;

use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Facades\App\Helpers\ResponseHelper;
use Facades\App\Repositories\UserRepositories;
use App\Http\Requests\Api\Admins\StoreMechanicRequest;
use App\Http\Requests\Api\Admins\UpdateMechanicRequest;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mechanics = Mechanic::with('user')->get();
        foreach ($mechanics as $key => $mechanic) {
            $mechanic->name = $mechanic->user->name;
            $mechanic->email = $mechanic->user->email;
        }
        return ResponseHelper::responseFormat(true, 'Mechanic Data', 200, $mechanics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMechanicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMechanicRequest $request)
    {
        $mechanic = DB::transaction(function () use ($request) {
            $mechanic = new Mechanic;
            $mechanic->save();

            $user = UserRepositories::create($request, $mechanic, 'App\Models\Mechanic');
            return $mechanic;
        });
        return ResponseHelper::responseFormat(true, 'Mechanic Created', 200, $mechanic);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mechanic = Mechanic::with('user')->findOrFail($id);
        $mechanic->name = $mechanic->user->name;
        $mechanic->email = $mechanic->user->email;
        return ResponseHelper::responseFormat(true, 'Mechanic Data', 200, $mechanic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMechanicRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMechanicRequest $request, $id)
    {
        $mechanic = Mechanic::with('user')->findOrFail($id);
        $user = UserRepositories::update($request, $mechanic->user->id);
        return ResponseHelper::responseFormat(true, 'Mechanic Updated', 200, $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mechanic = DB::transaction(function () use ($id) {
            $mechanic = Mechanic::with('user')->findOrFail($id);
            $user = $mechanic->user;
            $user->delete();
            $mechanic->delete();
        });
        return ResponseHelper::responseFormat(true, 'Mechanic Deleted', 200, $mechanic);
    }
}
