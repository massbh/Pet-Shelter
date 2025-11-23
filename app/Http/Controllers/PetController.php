<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::query();

        if ($request->has('species') && $request->species != '') {
            $query->where('species', $request->species);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $pets = $query->latest()->paginate(12);

        return view('pets.index', compact('pets'));
    }

    public function create()
    {
        return view('pets.create');
    }

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

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $pet = Pet::create($validated);
        
        // Returns to the gallery view after adding a pet
        return redirect()->route('pets.gallery')->with('success', 'Pet added successfully!');
    }

    public function edit(Pet $pet)
    {
        return view('pets.edit', compact('pet'));
    }

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

        if ($request->hasFile('image')) {
            if ($pet->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $pet->image_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pet->image_url));
            }
            
            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $pet->update($validated);

        return redirect()->route('pets.gallery')->with('success', 'Pet updated successfully!');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $pet->image_url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $pet->image_url));
        }

        $pet->delete();

        // Check if request expects JSON (AJAX request)
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet deleted successfully!'
            ]);
        }

        return redirect()->route('pets.gallery')->with('success', 'Pet deleted successfully!');
    }

    public function getPetsJson(Request $request)
    {
        $query = Pet::query();
        
        // Check if we should show all statuses (for admin gallery)
        $showAll = $request->query('show_all', false);
        
        if ($showAll === 'true' || $showAll === '1') {
            // Admin view - show all pets regardless of status
            $pets = $query->get();
        } else {
            // Public view - only show available pets (hide adopted and pending ones)
            $pets = $query->where('status', 'available')->get();
        }
        
        return response()->json($pets);
    }
}
