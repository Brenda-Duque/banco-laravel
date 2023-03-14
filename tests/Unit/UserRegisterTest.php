<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;

class UserRegisterTest extends TestCase
{

    public function test_columns_user_common() {

        $user = new User();

        $expected = [
            'type',
            'name',
            'email',
            'cpf_cnpj',
            'password',
            'transaction_password'
        ];
        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }

    public function test_column_shopkeeper() {
        $user = new User();
        $lojista = new LOjista();

        $expected = [
            'type',
            'name',
            'email',
            'cpf_cnpj',
            'password',
            'transaction_password'
        ];

        $expected_ = [
            'company_name'
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());
        $arrayCompared_ = array_diff($expected_, $lojista->getFillable());

        $this->assertEquals(0, count($arrayCompared));   
        $this->assertEquals(0, count($arrayCompared_));     
    }

    public function test_register_user_common() {

       $user = new User();

       $attributes = [
           'type'     => 'common',
           'name'     => 'Brenda Duque',
           'email'    => 'brendaduqueee@gmail.com',
           'cpf_cnpj' => '38530525094',
           'password' => 'Areia@2023',
           'transaction_password' => '123456'
       ];
       User::create($attributes);

       $this->assertDatabaseHas('users', [
           'type'     => 'common',
           'name'     => 'Brenda Duque',
           'email'    => 'brendaduqueee@gmail.com',
           'cpf_cnpj' => '38530525094',
           'password' => 'Areia@2023',
           'transaction_password' => '123456'
       ]);

       $this->assertTrue(true);
    }
}
