<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function create()
    {
        // Authorize admin-only access
        $this->authorize('create', Pet::class);
        
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
        // Authorize admin-only access
        $this->authorize('update', $pet);
        
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
        // Authorize admin-only access
        $this->authorize('delete', $pet);
        
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
        // Additional security: Check for SQL injection patterns in raw query string
        $rawQuery = $request->server('QUERY_STRING', '');
        
        // Decode URL encoding to catch encoded attacks
        $decodedQuery = urldecode($rawQuery);
        
        // SQL injection patterns to detect
        $sqlPatterns = [
            '/(%27)|(\')|(%23)|(#)/i',  // Single quotes and hash (URL encoded and raw)
            '/(%2D%2D)|(\-\-)/i',        // SQL comments (-- encoded and raw)
            '/((%27)|(\'))\s*or/i',      // ' OR or %27 OR (case insensitive)
            '/((%27)|(\'))\s*and/i',     // ' AND or %27 AND
            '/union.*select/i',          // UNION SELECT attacks
            '/drop\s+table/i',           // DROP TABLE
            '/delete\s+from/i',          // DELETE FROM
            '/update\s+.*set/i',         // UPDATE SET
            '/insert\s+into/i',          // INSERT INTO
            '/(\/\*)|(\*\/)|(%2F%2A)|(%2A%2F)/i', // Block comments
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $decodedQuery)) {
                return response()->json([
                    'error' => 'Potential SQL injection detected',
                    'message' => 'Invalid request parameters'
                ], 400);
            }
        }
        
        // Validate ALL query parameters to prevent injection attacks
        $validated = $request->validate([
            'show_all' => 'nullable|in:true,false,1,0',
            'available' => 'nullable|in:true,false,1,0',
            'species' => 'nullable|string|max:255|alpha',
            'status' => 'nullable|in:available,adopted,pending',
        ]);
        
        // Reject any unexpected query parameters
        $allowedParams = ['show_all', 'available', 'species', 'status'];
        $invalidParams = array_diff(array_keys($request->query()), $allowedParams);
        
        if (!empty($invalidParams)) {
            return response()->json([
                'error' => 'Invalid query parameters',
                'invalid_params' => $invalidParams
            ], 400);
        }
        
        $query = Pet::query();
        
        // Check if we should show all statuses (for admin gallery)
        $showAll = $validated['show_all'] ?? false;
        
        if ($showAll === 'true' || $showAll === '1') {
            // Admin view - show all pets regardless of status
            // Verify user is admin
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $pets = $query->get();
        } else {
            // Public view - only show available pets (hide adopted and pending ones)
            $pets = $query->where('status', 'available')->get();
        }
        
        return response()->json($pets);
    }
}
