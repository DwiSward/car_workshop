<?php

namespace App\Http\Controllers\Api\Admins;

use Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Repair;
use App\Models\Service;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use App\Models\RepairService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RepairServiceMechanic;
use Facades\App\Helpers\ResponseHelper;
use App\Http\Requests\Api\Admins\StoreAssignRequest;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repairs = Repair::with('repairServices', 'car.customer.user')
                        ->whereNotIn('status', [4])
                        ->get();
        foreach ($repairs as $key => $repair) {
            $repair->owner_name = $repair->car->customer->user->name;
        }
        return ResponseHelper::responseFormat(true, 'Repair Data', 200, $repairs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $repair = DB::transaction(function () use ($request) {
            // save reapir
            $repair = new Repair;   
            $repair->created_by = auth()->user()->id;
            $repair->car_id = $request->car_id;
            $repair->work_duration = $request->work_duration;
            $repair->status = 0;
            $repair->save();

            // save repair_services
            $services = [];
            $serviceIds = [];
            $servicePrices = Service::pluck('price', 'id')->toArray();
            foreach ($request->repairServices as $key => $service) {
                // check duplicate service
                if (in_array($service['service_id'], $serviceIds) ) {
                    abort(422, 'Duplicate service');
                }

                $services[] = [
                    'id' => Str::uuid()->toString(),
                    'repair_id' => $repair->id,
                    'service_id' => $service['service_id'],
                    'price' => $servicePrices[$service['service_id']],
                    'note' => $service['note'],
                    'qty' => $service['qty']
                ];
                $serviceIds[] = $service['service_id'];
            }
            RepairService::insert($services);
            return $repair;
        });
        return ResponseHelper::responseFormat(true, 'Repair Created', 200, $repair);
    }

    /**
     * Display a listing of the repair service for asign to mechanic.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairServices(Request $request)
    {
        $filter = json_decode($request->filter);
        $repairServices = RepairService::with('service', 'repair.car')
                                        ->whereNull('repair_service_id')
                                        ->when(isset($filter->repair_id), function ($query) use ($filter){
                                            $query->where('repair_id', $filter->repair_id);
                                        })
                                        ->get();
        foreach ($repairServices as $key => $repairService) {
            $repairService->car_brand = $repairService->repair->car->brand;
            $repairService->car_license_plate = $repairService->repair->car->license_plate;
            $repairService->car_color = $repairService->repair->car->color;
            $repairService->car_type = $repairService->repair->car->type;
            $repairService->service_name = $repairService->service->name;
        }
        return ResponseHelper::responseFormat(true, 'Repair Services', 200, $repairServices);
    }

    /**
     * Display a detail of the repair service for asign to mechanic.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairServicesShow($id)
    {
        $repairService = RepairService::with('service', 'repair.car')
                                        ->findOrFail($id);

        $repairService->car_brand = $repairService->repair->car->brand;
        $repairService->car_license_plate = $repairService->repair->car->license_plate;
        $repairService->car_color = $repairService->repair->car->color;
        $repairService->car_type = $repairService->repair->car->type;
        $repairService->service_name = $repairService->service->name;
        return ResponseHelper::responseFormat(true, 'Repair Services', 200, $repairService);
    }

    /**
     * Assign mechanics on repair service.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAssignMechanics(Request $request, $id)
    {
        $repairService = DB::transaction(function () use ($request, $id) {
            $repairService = RepairService::findOrFail($id);
            $repairService->status = 1;
            $repairService->save();
            $repair = Repair::with('repairServices.repairServiceMechanics')->findOrFail($repairService->repair_id);
            $mechanicIds = [];
            foreach ($repair->repairServices as $key => $repairService) {
                foreach ($repairService->repairServiceMechanics as $key => $repairServiceMechanic) {
                    $mechanicIds[] = $repairServiceMechanic->mechanic_id;
                }
            }

            $repairMechanics = [];
            foreach ($request->repairServices as $key => $mechanic) {
                if (in_array($mechanic, $mechanicIds)) {
                    abort(422, 'Duplicate mechanic');
                }

                $repairMechanics[] = [
                    'id' => Str::uuid()->toString(),
                    'repair_service_id' => $repairService->id,
                    'mechanic_id' => $mechanic['mechanic_id']
                ];
                $mechanicIds[] = $mechanic['mechanic_id'];
            }
            RepairServiceMechanic::insert($repairMechanics);
            return $repairService;
        });
        return ResponseHelper::responseFormat(true, 'Mechanic Assign', 200, $repairService);
    }

    /**
     * Display a listing of the repair service filter by mechanic login.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairServicesByMechanic()
    {
        $repairServices = RepairService::with('service', 'repair.car')
                                        ->whereHas('repairServiceMechanics', function($query) {
                                            $query->where('mechanic_id', auth()->user()->sourceable_id);
                                        })
                                        ->get();
        foreach ($repairServices as $key => $repairService) {
            $repairService->car_brand = $repairService->repair->car->brand;
            $repairService->car_license_plate = $repairService->repair->car->license_plate;
            $repairService->car_color = $repairService->repair->car->color;
            $repairService->car_type = $repairService->repair->car->type;
            $repairService->service_name = $repairService->service->name;
            $repairService->partner = '';
            foreach ($repairService->repairServiceMechanics as $key => $repairServiceMechanic) {
                if ($repairServiceMechanic->mechanic_id != auth()->user()->id) {
                    $repairService->partner .= $repairServiceMechanic->mechanic->user->name.' ';
                }
            }
        }
        return ResponseHelper::responseFormat(true, 'Repair Services', 200, $repairServices);
    }

    /**
     * Display a detail of the repair service filter by mechanic login.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairServicesByMechanicShow($id)
    {
        $repairService = RepairService::with('service', 'repair.car')
                                        ->findOrFail($id);

        $repairService->car_brand = $repairService->repair->car->brand;
        $repairService->car_license_plate = $repairService->repair->car->license_plate;
        $repairService->car_color = $repairService->repair->car->color;
        $repairService->car_type = $repairService->repair->car->type;
        $repairService->service_name = $repairService->service->name;
        $repairService->partner = '';
        foreach ($repairService->repairServiceMechanics as $key => $repairServiceMechanic) {
            if ($repairServiceMechanic->mechanic_id != auth()->user()->id) {
                $repairService->partner .= $repairServiceMechanic->mechanic->user->name;
            }
        }
        return ResponseHelper::responseFormat(true, 'Repair Services', 200, $repairService);
    }

    /**
     * set repair service done.
     *
     * @return \Illuminate\Http\Response
     */
    public function postDoneService(Request $request, $id)
    {
        $repairService = DB::transaction(function () use ($request, $id) {
            $repairService = RepairService::findOrFail($id);
            $repairService->status = $request->status;
            $repairService->save();

            $repairServices = RepairService::where('repair_id', $repairService->repair_id)
                                            ->whereIn('status', [0, 1])
                                            ->get();
            if (count($repairServices) == 0) {
                $repair = Repair::findOrFail($repairService->repair_id);
                $repair->status = 3;
                $repair->save();
            }
            return $repairService;
        });
        return ResponseHelper::responseFormat(true, 'Mechanic Assign', 200, $repairService);
    }

    /**
     * Display a listing of the resource done service.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInspect()
    {
        $repairs = Repair::with('repairServices', 'car.customer.user')
                        ->where('status', 3)
                        ->get();
        foreach ($repairs as $key => $repair) {
            $repair->owner_name = $repair->car->customer->user->name;
        }
        return ResponseHelper::responseFormat(true, 'Repair Data', 200, $repairs);
    }

     /**
     * Display the specified resource done service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInspectShow($id)
    {
        $repair = Repair::with('repairServices.service', 'car.customer.user')
                        ->with(['repairServices' => function ($query) {
                            $query->with('service')
                                ->whereNull('repair_service_id');
                        }])
                        ->findOrFail($id);
        $repair->owner_name = $repair->car->customer->user->name;
        $repair->car_brand = $repair->car->brand;
        foreach ($repair->repairServices as $key => $repairService) {
            $repairService->service_name = $repairService->service->name;
            $repairService->price = $repairService->price;
        }
        return ResponseHelper::responseFormat(true, 'Repair Data', 200, $repair);
    }

    /**
     * set complain service.
     *
     * @return \Illuminate\Http\Response
     */
    public function postComplaineService(Request $request, $id)
    {
        $newRepairService = DB::transaction(function () use ($request, $id) {
            if ($request->status == 4) {
                $repair = Repair::with(['repairServices' => function ($query) {
                                    $query->whereNull('repair_service_id');
                                }])
                                ->findOrFail($id);
                $repair->status = 4;
                $repair->save();

                $mail = Mail::to($repair->car->customer->user->email);
                $mail->send(new InvoiceMail($repair->id));
                return $repair;
            }
            else {
                $repair = Repair::findOrFail($id);
                $repair->status = 2;
                $repair->save();

                foreach ($request->repairServices as $key => $service) {
                    $repairService = RepairService::findOrFail($service['repair_service_id']);
                    $newRepairService = new RepairService;
                    $newRepairService->repair_id = $repairService->repair_id;
                    $newRepairService->service_id = $repairService->service_id;
                    $newRepairService->price = $repairService->price;
                    $newRepairService->qty = $repairService->qty;
                    $newRepairService->status = 0;
                    $newRepairService->note = $service['note'];
                    $newRepairService->save();

                    $repairService->repair_service_id = $newRepairService->id;
                    $repairService->save();
                    return $newRepairService;
                }
            }
        });

        return ResponseHelper::responseFormat(true, 'Mechanic Assign', 200, $newRepairService);
    }

    // /**
    //  * set complain service.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function postComplaineService(UpdateRepairServiceRequest $request)
    // {
    //     $newRepairService = DB::transaction(function () use ($request) {
    //         $repairService = RepairService::findOrFail($request->repair_service_id);
            
    //         $newRepairService = new RepairService;
    //         $newRepairService->repair_id = $repairService->repair_id;
    //         $newRepairService->service_id = $repairService->service_id;
    //         $newRepairService->price = $repairService->price;
    //         $newRepairService->status = $repairService->status;
    //         $newRepairService->note = $request->note;
    //         $newRepairService->save();

    //         $repairService->repair_service_id = $newRepairService->id;
    //         $repairService->save();
    //         return $newRepairService;
    //     });

    //     return ResponseHelper::responseFormat(true, 'Mechanic Assign', 200, $newRepairService);
    // }

    /**
     * set repair done.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairDone($id)
    {
        $repair = DB::transaction(function () use ($id) {
            $repair = Repair::findOrFail($id);
            $repair->status = 4;
            $repair->save();

            $mail = Mail::to($repair->car->customer->user->email);
            $mail->send(new InvoiceMail($repair->id));
            return $repair;
        });
        return ResponseHelper::responseFormat(true, 'Repair Done', 200, $repair);
    }

    /**
     * set cancel repair.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairCancel($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->status = 5;
        $repair->save();
        return ResponseHelper::responseFormat(true, 'Repair Canceled', 200, $repair);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $repair = Repair::with('repairServices.service', 'car.customer.user')->findOrFail($id);
        $repair->owner_name = $repair->car->customer->user->name;
        $repair->car_brand = $repair->car->brand;
        foreach ($repair->repairServices as $key => $repairService) {
            $repairService->service_name = $repairService->service->name;
            $repairService->price = $repairService->price;
        }
        return ResponseHelper::responseFormat(true, 'Repair Data', 200, $repair);
    }

    /**
     * set approve repair.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRepairApprove($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->status = 1;
        $repair->save();
        return ResponseHelper::responseFormat(true, 'Repair Canceled', 200, $repair);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $repair->status = $request->status;
        $repair->save();
        return ResponseHelper::responseFormat(true, 'Repair Updated', 200, $repair);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $car = Car::findOrFail($id);
    //     $car->save();
    //     return ResponseHelper::responseFormat(true, 'Car Deleted', 200, $car);
    // }

    // public function saveData($car, $request)
    // {
    //     $car->brand = $request->brand;
    //     $car->color = $request->color;
    //     $car->license_plate = $request->license_plate;
    //     $car->type = $request->type;
    //     $car->customer_id = $request->customer_id;
    //     $car->save();
    //     return $car;
    // }
}
