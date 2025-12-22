<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;

class AdoptionRequestPolicy
{
    /**
     * Determine if the user can view the adoption request.
     */
    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // Admins can view all requests, users can only view their own
        return $user->isAdmin() || $adoptionRequest->user_id === $user->id;
    }

    /**
     * Determine if the user can update the adoption request status.
     */
    public function updateStatus(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // Only admins can update status
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the adoption request.
     */
    public function delete(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // Admins can delete any request, users can delete their own
        return $user->isAdmin() || $adoptionRequest->user_id === $user->id;
    }
}
