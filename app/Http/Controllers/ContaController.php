<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContaController extends Controller
{
    public $balance = 0;
    public $holder;

    function deposit(){
        $this->saldo += $valor;
    }

    function balance() {
        echo "Saldo Atual:".$this->saldo. "<br>";
    }
}
