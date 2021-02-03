<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {   
        $user = auth('api')->user();

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);
        
        return Cart::where('user_id', (int)$user->id)->get();
        
    }

    public function showProducts()
    {   
        $user = auth('api')->user();

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);
        $cart = Cart::where('user_id', (int)$user->id)->get();

        if(count($cart)===1)
        return Cart::where('user_id', (int)$user->id)->get()[0]->products()->with("product")->get();
        else
        return [];
        
    }

    public function incrementProduct($id){
        $user = auth('api')->user();

        if($user===null)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        $productItem = ProductItem::find($id);
        $productItem->increment('quantity');
        $cart = $productItem->cart;
        $cart->total = $cart->total + $productItem->product->price;
        $cart->touch();
        $cart->save();

        return ProductItem::find($id);
    }

    public function decrementProduct($id){
        $user = auth('api')->user();

        if($user===null)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        ProductItem::find($id)->decrement('quantity');

        $productItem = ProductItem::find($id);

        $cart = $productItem->cart;
        $cart->total = $cart->total - $productItem->product->price;
        $cart->touch();
        $cart->save();

        if($productItem->quantity === 0){
            $productItem->delete();
        }

        return $productItem;
    }

    public function addProductToCart(Request $request){
        $data = $request->all();
        $user = auth('api')->user();
        $cart = $user->cart;

        if($user===null)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        if($cart===null){
            $data['user_id'] = $user->id;
            $data['total'] = 0;
            $cart = Cart::create($data);
        }
    
        $product = Product::find($data['product_id']);

        $productItems = $cart->products->all();

        $productItems = array_filter($productItems, function($productItem, $key) use ($product){
            return $productItem->product_id == $product->id;
        }, ARRAY_FILTER_USE_BOTH);

        if($product->user_id === $user->id)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'You cannot buy your product'],401);

        if(count($productItems) > 0)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Product already added to cart'],401);
        
        $cart->total = $cart->total + $product->price;
        $cart->save();

        $data['cart_id'] = $cart->id;
        $data['product_id'] = $product->id;

        $productItem = ProductItem::create($data);

        return $productItem;
    }

    public function show($id)
    {
        return Cart::find($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();

        $data = $request->json()->all();

        $cart = Cart::find($id);
        
        if($user===null || $cart->users_id != $user->id)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        $cart->name = $data['name'];

        $cart->image = $data['image'];

        $cart->price = (int)$data['price'] * 100;

        $cart->quantity = (int)$data['quantity'];

        $cart->touch();

        $cart->save();

        return ["status" => "ok", "code" => 200, 'message' => 'updated'];
    }

    public function destroyItem($id){
        $user = auth('api')->user();

        if($user===null)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        $productItem = ProductItem::find($id);
        $cart = $productItem->cart;
        $cart->total = $cart->total - $productItem->quantity * $productItem->product->price;
        $productItem::destroy($id);
        $cart->touch();
        $cart->save();
        return ["status" => "ok", "code" => 200, 'message' => 'deleted'];
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        $cart = Cart::find($id);

        if($user===null || $cart->users_id != $user->id)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        Cart::destroy($id);
        return ["status" => "ok", "code" => 200, 'message' => 'deleted'];
    }
}
