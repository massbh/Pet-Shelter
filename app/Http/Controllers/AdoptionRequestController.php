<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionRequestController extends Controller
{
    /**
     * Display a listing of all adoption requests (admin only)
     */
    public function index()
    {
        $requests = AdoptionRequest::with(['user', 'pet'])->latest()->paginate(15);
        return view('adoption-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new adoption request
     */
    public function create(Pet $pet)
    {
        // Check if user already has a pending request for this pet
        $existingRequest = AdoptionRequest::where('user_id', Auth::id())
            ->where('pet_id', $pet->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('pets.show', $pet)
                ->with('error', 'You already have a pending adoption request for this pet.');
        }

        return view('adoption-requests.create', compact('pet'));
    }

    /**
     * Store a newly created adoption request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'message' => 'nullable|string|max:1000',
        ]);

        // Check if user already has a pending request for this pet
        $existingRequest = AdoptionRequest::where('user_id', Auth::id())
            ->where('pet_id', $validated['pet_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('pets.show', $validated['pet_id'])
                ->with('error', 'You already have a pending adoption request for this pet.');
        }

        $adoptionRequest = AdoptionRequest::create([
            'user_id' => Auth::id(),
            'pet_id' => $validated['pet_id'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Your adoption request has been submitted successfully!');
    }

    /**
     * Display the specified adoption request
     */
    public function show(AdoptionRequest $adoptionRequest)
    {
        // Check authorization
        if (!Auth::user()->isAdmin() && $adoptionRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $adoptionRequest->load(['user', 'pet']);
        return view('adoption-requests.show', compact('adoptionRequest'));
    }

    /**
     * Update the status of an adoption request (admin only)
     */
    public function updateStatus(Request $request, AdoptionRequest $adoptionRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $adoptionRequest->update($validated);

        // If approved, update pet status
        if ($validated['status'] === 'approved') {
            $adoptionRequest->pet->update(['status' => 'adopted']);
        }

        return redirect()->back()
            ->with('success', 'Adoption request status updated successfully!');
    }

    /**
     * Remove the specified adoption request
     */
    public function destroy(AdoptionRequest $adoptionRequest)
    {
        // Check authorization - users can delete their own requests, admins can delete any
        if (!Auth::user()->isAdmin() && $adoptionRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $adoptionRequest->delete();

        return redirect()->back()
            ->with('success', 'Adoption request deleted successfully!');
    }

    /**
     * Display user's own adoption requests
     */
    public function myRequests()
    {
        $requests = AdoptionRequest::where('user_id', Auth::id())
            ->with('pet')
            ->latest()
            ->paginate(10);

        return view('adoption-requests.my-requests', compact('requests'));
    }
}
