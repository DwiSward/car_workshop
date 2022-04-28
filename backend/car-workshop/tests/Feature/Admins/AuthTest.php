<?php

namespace Tests\Feature\Admins;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * test login failed
     *
     * @return void
     */
    public function testLoginFailed()
    {
        $user = User::factory()->create();
        
        // Simulated login failed
        $response = $this->json('POST',url('api/admin/login'),[
            'email' => 'wrong'.$user->email,
            'password' => 'xxx',
        ]);

        $response->assertStatus(400);
    }

    /**
     * test login success
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = User::factory()->create();
        
        // Simulated login failed
        $response = $this->json('POST',url('api/admin/login'),[
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
