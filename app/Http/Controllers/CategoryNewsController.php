<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryNewsRequest;
use App\Http\Resources\SingleCategoryNewsResource;
use App\Models\CategoryNews;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryNews::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryNewsRequest $request)
    {

        $categoryNews = CategoryNews::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time())
        ]);

        return response()->json([
            'message' => 'Category news has been updated',
            'category-news' => new SingleCategoryNewsResource($categoryNews)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryNews  $categoryNews
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryNews $categoryNews)
    {
        return new SingleCategoryNewsResource($categoryNews);
//        return $categoryNews;
//        return "hello this code is running";
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryNews  $categoryNews
     * @return string
     */
    public function update(CategoryNewsRequest $request, CategoryNews $categoryNews)
    {
        $attributes = $request->toArray();
        $attributes['slug'] = Str::slug($request->name. '-' . time());
        $categoryNews->update($attributes);

        return response()->json([
            'message' => 'Category news has been updated',
            'Category-news' => new SingleCategoryNewsResource($categoryNews)
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryNews  $categoryNews
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CategoryNews $categoryNews)
    {

        try{
            $categoryNews->delete();

            return response()->json([
                'message' => 'Categry news has been deleted',
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Cannot delete this data'
            ]);
        }
    }
}
