<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Edufield;
use App\Models\Knowledge;
use App\Http\Controllers\Controller;
use App\Models\KnowledgeLevel;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('knowledge.index', [
            'knowledges' => Knowledge::all(),
            'edufields' => Edufield::orderBy('code_name')->get(),
            'knowledgeLevels' => KnowledgeLevel::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategoryId = request('subcategory');
        $subcategory = Subcategory::findOrFail($subcategoryId);

        return view('knowledge.create', [
            'subcategory' => $subcategory,
            'knowledges' => Knowledge::all()->where('subcategory', $subcategory)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', 'unique:knowledge,code_name'],
            'description' => ['required', 'string', 'max:2000'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
        ]);

        $originCourse = $request->get('course');
        $knowledge = Knowledge::create($validated);

        if ($originCourse) {
            return redirect()->route('course.edit', ['course' => $originCourse])
                ->with('notification', __('Knowledge :name created successfully.', ['name' => $knowledge->name]));
        } else {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Knowledge :name created successfully.', ['name' => $knowledge->name]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Knowledge $knowledge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Knowledge $knowledge)
    {
        $subcategoriesOrdered = Subcategory::select('subcategories.*')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->join('edufields', 'edufields.id', '=', 'categories.edufield_id')
            ->orderBy('edufields.name')
            ->orderBy('categories.name')
            ->orderBy('subcategories.name');

        return view('knowledge.edit', [
            'knowledge' => $knowledge,
            'subcategories' => $subcategoriesOrdered->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Knowledge $knowledge)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', Rule::unique('knowledge')->ignore($knowledge)],
            'description' => ['required', 'string', 'max:2000'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
        ]);

        $knowledge->fill($validated);
        $knowledge->save();

        return redirect()->route('knowledge.index')
            ->with('notification', __('Knowledge :name updated successfully.', ['name' => $knowledge->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Knowledge $knowledge)
    {
        //
    }
}
