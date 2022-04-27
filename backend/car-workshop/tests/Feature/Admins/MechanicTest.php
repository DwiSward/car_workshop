<?php

namespace Tests\Feature\Admins;

use Tests\TestCase;
use App\Models\User;
use App\Models\Mechanic;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;

class MechanicTest extends TestCase
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
     * test mechanic index
     *
     * @return void
     */
    public function testMechanicIndex()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('GET',url('api/admin/mechanics'));
        $response->assertStatus(200);
    }

    /**
     * test mechanic store name empty
     *
     * @return void
     */
    public function testMechanicStoreNameEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => null,
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store email empty
     *
     * @return void
     */
    public function testMechanicStoreEmailEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => null,
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store email not email value
     *
     * @return void
     */
    public function testMechanicStoreEmailNotEmail()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->name(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store email not unique
     *
     * @return void
     */
    public function testMechanicStoreEmailNotUnique()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => 'test@mail.com',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store password empty
     *
     * @return void
     */
    public function testMechanicStorePasswordEmpty()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => null,
                        'password_confirmation' => null,
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store password to short
     *
     * @return void
     */
    public function testMechanicStorePasswordToShort()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => '123',
                        'password_confirmation' => '123',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store password not match with confirm
     *
     * @return void
     */
    public function testMechanicStorePasswordNotConfirm()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'passwordx',
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic store success
     *
     * @return void
     */
    public function testMechanicStoreSuccess()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('POST',url('api/admin/mechanics'),[
                        'name' => $this->faker->name(),
                        'email' => $this->faker->unique()->safeEmail(),
                        'password' => 'password',
                        'password_confirmation' => 'password',
                    ]);

        $response->assertStatus(200);
    }

    // create mechanic for test update
    protected function createMechanic()
    {
        $mechanic = new Mechanic;
        $mechanic->save();

        $user = new User;
        $user->name = $this->faker->name();
        $user->email = $this->faker->unique()->safeEmail();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->sourceable_id = $mechanic->id;
        $user->sourceable_type = 'App\Models\Mechanic';
        $user->save();

        return $mechanic;
    }

    /**
     * test mechanic update wrong id
     *
     * @return void
     */
    public function testMechanicUpdateIdWrong()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/mechanics/0'),[
                        'name' => null,
                        'email' => $mechanic->user->email
                    ]);
        $response->assertStatus(404);
    }

    /**
     * test mechanic update name empty
     *
     * @return void
     */
    public function testMechanicUpdateNameEmpty()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/mechanics/'.$mechanic->id),[
                        'name' => null,
                        'email' => $mechanic->user->email
                    ]);
        $response->assertStatus(422);
    }

    /**
     * test mechanic update email empty
     *
     * @return void
     */
    public function testMechanicUpdateEmailEmpty()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/mechanics/'.$mechanic->id),[
                        'name' => $mechanic->user->name,
                        'email' => null
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic update email not email value
     *
     * @return void
     */
    public function testMechanicUpdateEmailNotEmail()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();
        $response = $this->withHeaders([
                        'Authorization' => 'Bearer '. $token,
                    ])
                    ->json('PUT',url('api/admin/mechanics/'.$mechanic->id),[
                        'name' => $mechanic->user->name,
                        'email' => $this->faker->name()
                    ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic update email not unique
     *
     * @return void
     */
    public function testMechanicUpdateEmailNotUnique()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('PUT',url('api/admin/mechanics/'.$mechanic->id),[
            'name' => $mechanic->user->name,
            'email' => 'test@mail.com'
        ]);

        $response->assertStatus(422);
    }

    /**
     * test mechanic update success
     *
     * @return void
     */
    public function testMechanicUpdateSuccess()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('PUT',url('api/admin/mechanics/'.$mechanic->id),[
            'name' => $mechanic->user->name.'x',
            'email' => $mechanic->user->email
        ]);

        $response->assertStatus(200);
    }

    /**
     * test mechanic delete wrong id
     *
     * @return void
     */
    public function testMechanicDeleteWrongId()
    {
        $token = $this->authenticate();
        // $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/mechanics/0'));

        $response->assertStatus(404);
    }

    /**
     * test mechanic delete success
     *
     * @return void
     */
    public function testMechanicDeleteSuccess()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('DELETE',url('api/admin/mechanics/'.$mechanic->id));

        $response->assertStatus(200);
    }

    /**
     * test mechanic show wrong id
     *
     * @return void
     */
    public function testMechanicShowWrongId()
    {
        $token = $this->authenticate();
        // $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/mechanics/0'));

        $response->assertStatus(404);
    }

    /**
     * test mechanic show success
     *
     * @return void
     */
    public function testMechanicShowSuccess()
    {
        $token = $this->authenticate();
        $mechanic = $this->createMechanic();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])
        ->json('GET',url('api/admin/mechanics/'.$mechanic->id));

        $response->assertStatus(200);
    }
}
