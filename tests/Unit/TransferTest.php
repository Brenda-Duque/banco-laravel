<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class TransferTest extends TestCase
{
    public function test_transfer() {
        $user = User::where('cpf_cnpj', '52734403005')->first();

        $bodyTransfer = [
            'value' => '2',
            'account_transfer' => '1629520'
        ];

        $responseTransfer = $this->actingAs($user)->post('api/transfer', $bodyTransfer);
        $responseTransfer->assertStatus(200);
    }
}
