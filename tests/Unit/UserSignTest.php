<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Service\Users\UserServiceSign;

class UserSignTest extends TestCase
{
    public function test_login() {
        $body = [
            'cpf_cnpj' => '62780371064',
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
