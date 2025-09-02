<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeLevel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KnowledgeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('knowledge-level.index', [
            'knowledgeLevels' => KnowledgeLevel::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeLevel $knowledgeLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeLevel $knowledgeLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KnowledgeLevel $knowledgeLevel)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'weight' => ['required', 'numeric', 'min:0']
        ]);

        $knowledgeLevel->update($validated);

        return redirect()->route('knowledge-level.index')
            ->with('notification', __('Knowledge Level :name updated successfully.', ['name' => $knowledgeLevel->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeLevel $knowledgeLevel)
    {
        //
    }
}
