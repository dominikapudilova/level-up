<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Edufield;
use App\Models\Subcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $subcategories = Subcategory::all()->where('category', $category);

        return view('subcategory.create', [
            'category' => $category,
            'subcategoriesImploded' => $subcategories->implode('name', ', ')
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

        $subcategory = Subcategory::create($validated);
        $originCourse = $request->get('course');

        if ($originCourse) {
            return redirect()->route('course.edit', ['course' => $originCourse])
                ->with('notification', __('Subcategory :name created successfully.', ['name' => $subcategory->name]));
        } else {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Subcategory :name created successfully.', ['name' => $subcategory->name]));
        }
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
        $categoriesOrdered = Category::select('categories.*')
            ->join('edufields', 'edufields.id', '=', 'categories.edufield_id')
            ->orderBy('edufields.name')
            ->orderBy('categories.name')
            ->get();

        return view('subcategory.edit', [
            'subcategory' => $subcategory,
            'categories' => $categoriesOrdered
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', Rule::unique('subcategories')->ignore($subcategory)],
            'description' => ['required', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $subcategory->fill($validated);
        $subcategory->save();

        $originCourse = $request->get('course');

        if (!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Subcategory :name updated successfully.', ['name' => $subcategory->name]));
        }
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Subcategory :name updated successfully.', ['name' => $subcategory->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        //
    }
}
