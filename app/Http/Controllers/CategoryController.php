<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Project;

class CategoryController extends Controller
{
    public function landing()
    {
        // Load projects with their related services
        $projects = Project::all()->map(function ($project) {
            $serviceKeys = is_array($project->services)
                ? $project->services
                : json_decode($project->services, true);

            $mappedServices = collect($serviceKeys)->map(function ($key) {
                return [
                    'key' => $key,
                    'name' => config("global.services.$key"),
                ];
            });

            $project->services = $mappedServices;
            return $project;
        });


        return view('category.landing', compact('projects'));
    }

    public function index()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $projectId = $request->input('project');
        $serviceKey = $request->input('service');
        $serviceName = config("global.services.$serviceKey");

        return view('category.create', compact('projectId', 'serviceKey', 'serviceName'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project' => 'required|exists:projects,id',
            'service' => 'required|string|in:' . implode(',', array_keys(config('global.services'))),
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'project_id' => $validated['project'],
            'service' => $validated['service'],
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }


    public function show(string $id) {}

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'service' => $request->service,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
        ]);
    }

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
        $services = Category::distinct()->pluck('service');
        return response()->json($services);
    }

    public function getProjectsByService($serviceKey)
    {
        $projects = Project::where('service', $serviceKey)->get(['id', 'name']);
        return response()->json($projects);
    }
}
