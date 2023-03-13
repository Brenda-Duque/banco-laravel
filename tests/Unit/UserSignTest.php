<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Service\Users\UserServiceSing;

class UserSignTest extends TestCase
{
    public function test_login() {
        $body = [
            'cpf_cnpj' => '52734403005',
            'password' => 'testandoA@0',
        ];
        $response = $this->post('/api/login', $body);
 
        $response->assertStatus(200);

        $responseLogin = $response->getOriginalContent();

        $responseT = $this->withHeaders([
            'x-access-token' => "Bearer ".$responseLogin['access_token'],
        ])->get('/api/user');

        $responseT->assertOk();
    }
}
