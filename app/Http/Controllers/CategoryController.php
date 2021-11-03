<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategorySingleResource;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name . "-" . time())),
        ]);

        return response()->json([
            'message' => 'Category has been created',
            'Category' => new CategorySingleResource($category)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategorySingleResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $attributes = $request->toArray();
        $attributes['slug'] = Str::slug($request->name. '-' . time());
        $category->update($attributes);
//        $category->update([
//            'name' => $request->name,
//            'slug' => Str::slug($request->name. '-' . time())
//        ]);

        return response()->json([
            'message' => 'Category has been updated',
            'Category' => new CategorySingleResource($category)
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'scategory has been deleted'
        ]);
    }
}
