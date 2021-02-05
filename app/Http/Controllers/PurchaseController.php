<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;

class PurchaseController extends Controller
{

    public function index(){

        $status = ["requires_payment_method" => 0, "unpaid" => 0, "processing" => 1, "succeeded" => 2];
        
        $user = auth('api')->user();

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        $data = Purchase::with("cart")->get();

        $data->map(function ($record) use ($status){
            $purchase = Purchase::find($record->id);

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'bearer '.env('API_KEY_STRIPE', ''),
            ])->post('https://api.stripe.com/v1/payment_intents/'.$purchase->payment->transaction_code);

            $purchase['status'] = $status[$response['status']];
            $record->status = $status[$response['status']];
            $purchase->touch();
            $purchase->save();
        });

        return $data;
    }

    static public function create($user)
    {   
        $cart = $user->cart;
        $cart->user_id = null;
        $cart->save();

        $purchase = Purchase::create(["user_id" => $user->id, "cart_id" => $cart->id, "buyer_email" => $user->email, "status" => 0]);
        return $purchase;
    }

}
