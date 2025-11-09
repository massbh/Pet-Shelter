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
        // Prevent admins from creating adoption requests
        if (Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Administrators cannot submit adoption requests.');
        }

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
        // Prevent admins from creating adoption requests
        if (Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Administrators cannot submit adoption requests.');
        }

        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $existingRequest = AdoptionRequest::where('user_id', Auth::id())
            ->where('pet_id', $validated['pet_id'])
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return redirect()->route('pets.show', $validated['pet_id'])
                ->with('error', 'You already have a pending adoption request for this pet.');
        }

        AdoptionRequest::create([
            'user_id' => Auth::id(),
            'pet_id' => $validated['pet_id'],
            'message' => $validated['message'],
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

        $adoptionRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        if ($validated['status'] === 'approved') {
            $adoptionRequest->pet->update(['status' => 'adopted']);
        }

        $statusMessage = ucfirst($validated['status']);
        return redirect()->route('dashboard')
            ->with('success', "Adoption request {$statusMessage} successfully!");
    }

    /**
     * Remove the specified adoption request
     */
    public function destroy(AdoptionRequest $adoptionRequest)
    {
        // Only allow users to delete their own requests, or admins to delete any
        if (!Auth::user()->isAdmin() && $adoptionRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $adoptionRequest->delete();

        return redirect()->route('dashboard')
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
