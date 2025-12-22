<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdoptionRequest;

class VerifyAdoptionRequestOwnership
{
    /**
     * Handle an incoming request to verify the user owns the adoption request
     * or is an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/signin')->with('error', 'Please login to access this page.');
        }

        // Get the adoption request from route parameter
        $adoptionRequest = $request->route('adoptionRequest');
        
        // If adoptionRequest exists and user is not admin
        if ($adoptionRequest && !auth()->user()->isAdmin()) {
            // Verify the user owns this adoption request
            if ($adoptionRequest->user_id !== auth()->id()) {
                abort(403, 'You do not have permission to access this adoption request.');
            }
        }

        return $next($request);
    }
}
