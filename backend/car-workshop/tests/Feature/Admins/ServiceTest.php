<?php

namespace Tests\Feature\Admins;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;

class ServiceTest extends TestCase
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
     * test service index
     *
     * @return void
     */
    public function testServiceIndex()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('GET',url('api/admin/services'));
        $response->assertStatus(200);
    }

    /**
     * test service store name empty
     *
     * @return void
     */
    public function testServiceStoreNameEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/services'),[
                        'name' => null,
                        'price' => 100000
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test service store name duplicate
     *
     * @return void
     */
    public function testServiceStoreNameDuplicate()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/services'),[
                        'name' => $service->name,
                        'price' => 100000
                    ]);

        $response->assertStatus(422);
    }

    
    /**
     * test service store success
     *
     * @return void
     */
    public function testServiceStoreSuccess()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/services'),[
                        'name' => $this->faker->unique()->word,
                        'price' => 100000
                    ]);

        $response->assertStatus(200);
    }

    /**
     * test service update wrong id
     *
     * @return void
     */
    public function testServiceUpdateIdWrong()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/services/0'),[
                        'name' => $this->faker->unique()->word,
                        'price' => 100000
                    ]);
        $response->assertStatus(404);
    }

    /**
     * test service update name empty
     *
     * @return void
     */
    public function testServiceUpdateNameEmpty()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/services/'.$service->id),[
                        'name' => null,
                        'price' => 100000
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test service update name duplicate
     *
     * @return void
     */
    public function testServiceUpdateNameDuplicate()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();
        $service2 = Service::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/services/'.$service->id),[
                        'name' => $service2->name,
                        'price' => 100000
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test service update success
     *
     * @return void
     */
    public function testServiceUpdateSuccess()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/services/'.$service->id),[
                        'name' => $service->name.'x',
                        'price' => 100000
                    ]);

        $response->assertStatus(200);
    }

    /**
     * test service delete wrong id
     *
     * @return void
     */
    public function testServiceDeleteWrongId()
    {
        $token = $this->authenticate();
        // $service = Service::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/services/0'));

        $response->assertStatus(404);
    }

    /**
     * test service delete success
     *
     * @return void
     */
    public function testServiceDeleteSuccess()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/services/'.$service->id));

        $response->assertStatus(200);
    }

    /**
     * test service show wrong id
     *
     * @return void
     */
    public function testServiceShowWrongId()
    {
        $token = $this->authenticate();
        // $service = Service::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/services/0'));

        $response->assertStatus(404);
    }

    /**
     * test service show success
     *
     * @return void
     */
    public function testServiceShowSuccess()
    {
        $token = $this->authenticate();
        $service = Service::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/services/'.$service->id));

        $response->assertStatus(200);
    }
}
