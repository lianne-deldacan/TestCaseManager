<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service' => 'required|string|max:255'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'category' => $category
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service' => 'required|string|max:255'
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }

    public function getUniqueServices()
    {
        // Get unique services from the categories table
        $services = Category::distinct()->pluck('service');
        return response()->json($services);
    }

    public function getCategoriesByService($service)
    {
        // Get categories where service matches
        $categories = Category::where('service', $service)->pluck('name', 'id');
        return response()->json($categories);
    }
    
}


