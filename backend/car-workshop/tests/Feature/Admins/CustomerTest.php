<?php

namespace Tests\Feature\Admins;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;

class CustomerTest extends TestCase
{
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
     * test customer index
     *
     * @return void
     */
    public function testCustomerIndex()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('GET',url('api/admin/customers'));
        $response->assertStatus(200);
    }

    /**
     * test customer store name empty
     *
     * @return void
     */
    public function testCustomerStoreNameEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => null,
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store email empty
     *
     * @return void
     */
    public function testCustomerStoreEmailEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => null,
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store email not email value
     *
     * @return void
     */
    public function testCustomerStoreEmailNotEmail()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->name(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store email not unique
     *
     * @return void
     */
    public function testCustomerStoreEmailNotUnique()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => 'test@mail.com',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store password empty
     *
     * @return void
     */
    public function testCustomerStorePasswordEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => null,
                        'password_confirmation' => null,
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store password to short
     *
     * @return void
     */
    public function testCustomerStorePasswordToShort()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => '123',
                        'password_confirmation' => '123',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store password not match with confirm
     *
     * @return void
     */
    public function testCustomerStorePasswordNotConfirm()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'passwordx',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer store success
     *
     * @return void
     */
    public function testCustomerStoreSuccess()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/customers'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(200);
    }

    // create customer for test update
    protected function createCustomer()
    {
        $customer = new Customer;
        $customer->save();

        $user = new User;
        $user->name = $this->faker->name();
        $user->email = $this->faker->unique()->safeEmail();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->sourceable_id = $customer->id;
        $user->sourceable_type = 'App\Models\Customer';
        $user->save();

        return $customer;
    }

    /**
     * test customer update wrong id
     *
     * @return void
     */
    public function testCustomerUpdateIdWrong()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/customers/0'),[
                        'name' => null,
                        'email' => $customer->user->email
                    ]);
        $response->assertStatus(404);
    }

    /**
     * test customer update name empty
     *
     * @return void
     */
    public function testCustomerUpdateNameEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/customers/'.$customer->id),[
                        'name' => null,
                        'email' => $customer->user->email
                    ]);
        $response->assertStatus(422);
    }

    /**
     * test customer update email empty
     *
     * @return void
     */
    public function testCustomerUpdateEmailEmpty()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/customers/'.$customer->id),[
                        'name' => $customer->user->name,
                        'email' => null
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer update email not email value
     *
     * @return void
     */
    public function testCustomerUpdateEmailNotEmail()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/customers/'.$customer->id),[
                        'name' => $customer->user->name,
                        'email' => $this->faker->name()
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test customer update email not unique
     *
     * @return void
     */
    public function testCustomerUpdateEmailNotUnique()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('PUT',url('api/admin/customers/'.$customer->id),[
            'name' => $customer->user->name,
            'email' => 'test@mail.com'
        ]);

        $response->assertStatus(422);
    }

    /**
     * test customer update success
     *
     * @return void
     */
    public function testCustomerUpdateSuccess()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('PUT',url('api/admin/customers/'.$customer->id),[
            'name' => $customer->user->name.'x',
            'email' => $customer->user->email
        ]);

        $response->assertStatus(200);
    }

    /**
     * test customer delete wrong id
     *
     * @return void
     */
    public function testCustomerDeleteWrongId()
    {
        $token = $this->authenticate();
        // $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/customers/0'));

        $response->assertStatus(404);
    }

    /**
     * test customer delete success
     *
     * @return void
     */
    public function testCustomerDeleteSuccess()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/customers/'.$customer->id));

        $response->assertStatus(200);
    }

    /**
     * test customer show wrong id
     *
     * @return void
     */
    public function testCustomerShowWrongId()
    {
        $token = $this->authenticate();
        // $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/customers/0'));

        $response->assertStatus(404);
    }

    /**
     * test customer show success
     *
     * @return void
     */
    public function testCustomerShowSuccess()
    {
        $token = $this->authenticate();
        $customer = $this->createCustomer();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/customers/'.$customer->id));

        $response->assertStatus(200);
    }
}
