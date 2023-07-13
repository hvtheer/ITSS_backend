<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pay;

class PayController extends Controller
{
    public function payment(Request $request){
        $validatedData = $request->validate([
            'card_number' => 'required|string|numeric',
            'password' => 'required',
            'pay_money' => 'required|numeric'
        ]);

        $pay_user = Pay::where('card_number', $request->input('card_number'))->first();
        
        if ($pay_user){
            if ($pay_user->password == $validatedData['password']){
                if ($pay_user->balance >=(int)$validatedData['pay_money'] ){
                    $pay_user->balance = $pay_user->balance - (int)$validatedData['pay_money'];
                    $pay_user->save();
                    return response([
                        'success' => "True",
                        'message' => "Thanh toán thành công"
                    ]);
                }
                else{
                    return response([
                        'success' => "False",
                        'message' => "Thanh toán thất bại ! Số dư tài khoản không đủ để thực hiện giao dịch"
                    ]);
                }
                
            } 
            else{
                return response([
                    'success' => "False",
                    'message' => "Mật khẩu không đúng "
                ]);
            }          
        }

        return response([
            'success' => "False",
            'message' => "Số tài khoản không tồn tại"
        ]);
    }
}

