<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Edufield;
use App\Models\Subcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryId = request('category');
        $category = Category::findOrFail($categoryId);

        return view('subcategory.create', [
            'category' => $category,
            'subcategories' => Subcategory::all()->where('category', $category)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', 'unique:subcategories,code_name'],
            'description' => ['required', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $originCourse = $request->get('course');

        $subcategory = Subcategory::create($validated);
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Subcategory :name created successfully.', ['name' => $subcategory->name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        //
    }
}
