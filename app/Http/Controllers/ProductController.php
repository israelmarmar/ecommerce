<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index()
    {   
        $user = auth('api')->user();

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);
        
        return Product::where('user_id', (int)$user->id)->get();
        
    }

    public function showAll()
    {  
        return Product::all();
    }

    public function create(Request $request)
    {   
        $user = auth('api')->user();
        $data = $request->all();

        if($user===null)
        return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);
        
        $data['user_id'] = $user->id;
        $data['price'] = (int)$data['price'] * 100;
        
        Product::create($data);
        return $request->all();
    }

    public function show($id)
    {
        return Product::find($id);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();

        $data = $request->json()->all();

        $product = Product::find($id);
        
        if($user===null || $product->user_id != $user->id)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        $product->name = $data['name'];

        $product->image = $data['image'];

        $product->price = (int)$data['price'] * 100;

        $product->quantity = (int)$data['quantity'];

        $product->touch();

        $product->save();

        return ["status" => "ok", "code" => 200, 'message' => 'updated'];
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        $product = Product::find($id);

        if($user===null || $product->user_id != $user->id)
            return Response()->json(["status" => "ok", "code" => 401, 'message' => 'Unauthorized'],401);

        Product::destroy($id);
        return ["status" => "ok", "code" => 200, 'message' => 'deleted'];
    }
}
