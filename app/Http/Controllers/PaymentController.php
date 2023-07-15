<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPost(Request $request) {

        $dataArray      =        array(
            "card_number"        =>      $request->card_number,
            "password"         =>      $request->password,
            "pay_money"          =>      $request->pay_money
        );

        $response       =        Http::post("http://127.0.0.1:8000/api/pay", $dataArray);

        $response       =        $response->body();
        print_r($response);
    }
}
