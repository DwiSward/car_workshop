<?php

namespace Tests\Feature\Admins;

use App\Models\Car;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;

class CarTest extends TestCase
{
    private $type = [
        'sedan', 
        'hatchback', 
        'suv', 
        'coupe', 
        'convertible', 
        'crossover', 
        'van', 'pickup', 
        'truck', 
        'bus', 
        'motorcycle', 
        'other'
    ];
    use WithFaker;
    /**
     * Authenticate user.
     *
     * @return void
     */
    protected function authenticate()
    {
        $user = User::firstOrCreate([
            'name' => 'test',
            'email' => 'test@mail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
        ]);

        if (!$token = JWTAuth::attempt(['email'=>$user->email, 'password'=>'password'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return $token;
    }
    
    /**
     * test car index
     *
     * @return void
     */
    public function testCarIndex()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('GET',url('api/admin/cars'));
        $response->assertStatus(200);
    }

    // create customer for relation to car
    protected function createCustomer()
    {
        $customer = new Customer;
        $customer->save();
        return $customer;
    }

    /**
     * test car store brand empty
     *
     * @return void
     */
    public function testCarStoreBrandEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => null,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store color empty
     *
     * @return void
     */
    public function testCarStoreColorEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => null,
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store license plate empty
     *
     * @return void
     */
    public function testCarStoreLicensePlateEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => null,
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store license plate duplicate
     *
     * @return void
     */
    public function testCarStoreLicensePlateDuplicate()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $car->license_plate,
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(422);
    }
    
    /**
     * test car store type empty
     *
     * @return void
     */
    public function testCarStoreTypeEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => null,
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store customer_id empty
     *
     * @return void
     */
    public function testCarStoreCustomerIdEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => null
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store customer_id wrong
     *
     * @return void
     */
    public function testCarStoreCustomerIdWrong()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => '0'
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car store success
     *
     * @return void
     */
    public function testCarStoreSuccess()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/cars'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);

        $response->assertStatus(200);
    }

    /**
     * test car update wrong id
     *
     * @return void
     */
    public function testCarUpdateIdWrong()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/0'),[
                        'brand' => $this->faker->company,
                        'color' => $this->faker->colorName(),
                        'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
                        'type' => $this->faker->randomElement($this->type),
                        'customer_id' => $customer->id
                    ]);
        $response->assertStatus(404);
    }

    /**
     * test car update brand empty
     *
     * @return void
     */
    public function testCarUpdateBrandEmpty()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => null,
                        'color' => $car->color,
                        'license_plate' => $car->license_plate,
                        'type' => $car->type,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update color empty
     *
     * @return void
     */
    public function testCarUpdateColorEmpty()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => null,
                        'license_plate' => $car->license_plate,
                        'type' => $car->type,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update license plate empty
     *
     * @return void
     */
    public function testCarUpdateLicensePlateEmpty()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => $car->color,
                        'license_plate' => null,
                        'type' => $car->type,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update license plate duplicate
     *
     * @return void
     */
    public function testCarUpdateLicensePlateDuplicate()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $car2 = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => $car->color,
                        'license_plate' => $car2->license_plate,
                        'type' => $car->type,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(422);
    }
    
    /**
     * test car update type empty
     *
     * @return void
     */
    public function testCarUpdateTypeEmpty()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => $car->color,
                        'license_plate' => $car->license_plate,
                        'type' => null,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update customer_id empty
     *
     * @return void
     */
    public function testCarUpdateCustomerIdEmpty()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => $car->color,
                        'license_plate' => $car->license_plate,
                        'type' => $car->type,
                        'customer_id' => null
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update customer_id wrong
     *
     * @return void
     */
    public function testCarUpdateCustomerIdWrong()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand,
                        'color' => $car->color,
                        'license_plate' => $car->license_plate,
                        'type' => $car->type,
                        'customer_id' => 0
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test car update success
     *
     * @return void
     */
    public function testCarUpdateSuccess()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/cars/'.$car->id),[
                        'brand' => $car->brand.'x',
                        'color' => $car->color,
                        'license_plate' => $car->license_plate,
                        'type' => $car->type,
                        'customer_id' => $car->customer_id
                    ]);

        $response->assertStatus(200);
    }

    /**
     * test car delete wrong id
     *
     * @return void
     */
    public function testCarDeleteWrongId()
    {
        $token = $this->authenticate();
        // $car = Car::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/cars/0'));

        $response->assertStatus(404);
    }

    /**
     * test car delete success
     *
     * @return void
     */
    public function testCarDeleteSuccess()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/cars/'.$car->id));

        $response->assertStatus(200);
    }

    /**
     * test car show wrong id
     *
     * @return void
     */
    public function testCarShowWrongId()
    {
        $token = $this->authenticate();
        // $car = Car::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/cars/0'));

        $response->assertStatus(404);
    }

    /**
     * test car show success
     *
     * @return void
     */
    public function testCarShowSuccess()
    {
        $token = $this->authenticate();
        $car = Car::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/cars/'.$car->id));

        $response->assertStatus(200);
    }
}
