<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pet::query();

        // Filter by species if provided
        if ($request->has('species') && $request->species != '') {
            $query->where('species', $request->species);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $pets = $query->latest()->paginate(12);

        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'sex' => 'required|in:Male,Female',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $pet = Pet::create($validated);

        return redirect()->route('pets.show', $pet)->with('success', 'Pet added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'sex' => 'required|in:Male,Female',
            'description' => 'nullable|string',
            'status' => 'required|in:available,adopted,pending',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $pet->image_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pet->image_url));
            }
            
            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $pet->update($validated);

        return redirect()->route('pets.show', $pet)->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        // Delete image if exists
        if ($pet->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $pet->image_url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $pet->image_url));
        }

        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet deleted successfully!');
    }

    /**
     * Get pets as JSON for API or AJAX requests
     */
    public function getPetsJson()
    {
        $pets = Pet::where('status', 'available')->get();
        return response()->json($pets);
    }
}
