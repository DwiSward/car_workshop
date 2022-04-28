<?php

namespace App\Http\Controllers\Api\Admins;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Facades\App\Helpers\ResponseHelper;
use Facades\App\Repositories\UserRepositories;
use App\Http\Requests\Api\Admins\StoreCustomerRequest;
use App\Http\Requests\Api\Admins\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with('user')->get();
        foreach ($customers as $key => $customer) {
            $customer->name = @$customer->user->name;
            $customer->email = @$customer->user->email;
        }
        return ResponseHelper::responseFormat(true, 'Customer Data', 200, $customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = DB::transaction(function () use ($request) {
            $customer = new Customer;
            $customer->save();

            $user = UserRepositories::create($request, $customer);
            return $customer;
        });
        // return ResponseHelper::responseFormat(true, 'Customer Created', 200, $customer);
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $customer->name = @$customer->user->name;
        $customer->email = @$customer->user->email;
        return ResponseHelper::responseFormat(true, 'Customer Data', 200, $customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $user = UserRepositories::update($request, $customer->user->id);
        return ResponseHelper::responseFormat(true, 'Customer Updated', 200, $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = DB::transaction(function () use ($id) {
            $customer = Customer::with('user')->findOrFail($id);
            $user = $customer->user;
            $user->delete();
            $customer->delete();
            return $customer;
        });
        return ResponseHelper::responseFormat(true, 'Customer Deleted', 200, $customer);
    }
}
