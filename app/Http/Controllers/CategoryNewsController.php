<?php

namespace App\Http\Controllers;

use App\Http\Resources\SingleCategoryNewsResource;
use App\Models\CategoryNews;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryNews $categoryNews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryNews  $categoryNews
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryNews $categoryNews)
    {
        //
    }
}
