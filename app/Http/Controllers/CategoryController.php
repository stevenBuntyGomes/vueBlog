<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        // $category = Category::insert($request->except('_token', 'created_at') + [
        //     'created_at' => Carbon::now(),
        // ]);
        $category_id = Category::insertGetId([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
            'created_at' => Carbon::now(),
        ]);

        $get_category = Category::where('id', $category_id)->first();

        return response($get_category);
    }


    public function storeImage(Request $request){
        // $request->vlidate([
        //     'file' => 'required|mimes:jpeg,bmp,png',
        // ]);

        $picName = time() . '.' . $request->file->extension();
        $request->file->move(base_path('public/uploads/category/'), $picName);
        return response($picName);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        $editCat = Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
        return response()->json($editCat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        $fileName = $request->iconImage;
        $file_path = base_path('public/uploads/category/') . $fileName;
        if(file_exists($file_path)){
            unlink($file_path);
        }

        return Category::where('id', $request->id)->delete();


    }

    public function delete_image(Request $request){
        $fileName = $request->imgName;
        $file_path = base_path('public/uploads/category/') . $fileName;
        if(file_exists($file_path)){
            unlink($file_path);
        }
        return response('done');
    }
}
