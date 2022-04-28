<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Mechanic;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@mail.com';
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->save();

        $customer = new Customer;
        $customer->save();
        $user = new User;
        $user->name = 'Owner';
        $user->email = 'owner@mail.com';
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->sourceable_id = $customer->id;
        $user->sourceable_type = 'App\Models\Customer';
        $user->save();
        $car = new Car;
        $car->brand = 'Avansa';
        $car->color = 'Red';
        $car->license_plate = 'DK 1234 LK';
        $car->type = '001';
        $car->customer_id = $customer->id;
        $car->save();

        $mechanic = new Mechanic;
        $mechanic->save();
        $user = new User;
        $user->name = 'Mechanic';
        $user->email = 'mechanic@mail.com';
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->sourceable_id = $mechanic->id;
        $user->sourceable_type = 'App\Models\Mechanic';
        $user->save();

        $service = new Service;
        $service->name = 'Aki Replacement';
        $service->price = 100000;
        $service->save();
    }
}
