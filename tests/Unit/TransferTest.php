<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class TransferTest extends TestCase
{
    public function test_transfer() {
        $user = User::where('cpf_cnpj', '62780371064')->first();

        $bodyTransfer = [
            'value' => '2',
            'account_transfer' => '7765483',
            'transaction_password' => '123456'
        ];

        $responseTransfer = $this->actingAs($user)->post('api/transfer', $bodyTransfer);
        $responseTransfer->assertStatus(200);
    }
}
