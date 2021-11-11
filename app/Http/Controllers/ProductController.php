<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $product = ProductResource::collection(Product::orderBy('created_at','desc')->get());
            return response()->json([
                "status" => "ok",
                "message" => "Show all data",
                "data" => $product
            ], 200);
        }catch (\Error $e){
            return response()->json([
                "status" => 'error',
                'message' => 'Maaf terjadi kesalahan pada server'
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {

        try{
            if ($request->price < 10000) {
                throw ValidationException::withMessages([
                    'price' => 'Your price is too low'
                ]);
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => strtolower(Str::slug($request->name . '-' . time())),
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'status' => 'oke',
                'message' => 'Product has been created',
                'product' => new SingleProductResource($product),
            ], 201);

        }catch (\Error $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf terjadi kesalahan pada sistem kami '
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
       return response()->json([
           'status' => 'oke',
           'message' => 'Show Single data',
           'data' =>  new SingleProductResource($product),
       ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        $attributes = $request->toArray();
        $attributes['slug'] = Str::slug($request->name . '-' . time());
        $product->update($attributes);

        return response()->json([
            'status' => 'oke',
            'message' => 'Product has been updated',
            'product' => new SingleProductResource($product)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'oke',
            'message' => 'product has been deleted'
        ], 200);
    }
}
