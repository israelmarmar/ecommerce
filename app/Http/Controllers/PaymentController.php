<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Purchase;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\App;


class PaymentController extends Controller
{

    public function checkout()
    {   
        $YOUR_DOMAIN = 'http://localhost:8000';
        $user = auth('api')->user();

        \Stripe\Stripe::setApiKey(env('API_KEY_STRIPE', ''));

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);
    
        $purchase = PurchaseController::create($user);

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $purchase->cart->total,
                'product_data' => [
                  'name' => 'Purchase',
                  'images' => ["https://media.istockphoto.com/vectors/shopping-cart-icon-isolated-on-white-background-vector-id1206806317?k=6&m=1206806317&s=612x612&w=0&h=Fo7D7nh_QPu758KRdbNTp7m4xSVOxBvJ2cfUvA1_k_U="],
                ],
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/page/purchases',
            'cancel_url' => $YOUR_DOMAIN . '/page/cart',
          ]);

        Payment::create(["gateway" => "Stripe", "transaction_code" => $checkout_session->payment_intent, "purchase_id" => $purchase->id]);
        
        return $checkout_session;

    }

}
