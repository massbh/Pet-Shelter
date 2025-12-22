<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    /**
     * Determine if the user can create pets.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the pet.
     */
    public function update(User $user, Pet $pet): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the pet.
     */
    public function delete(User $user, Pet $pet): bool
    {
        return $user->isAdmin();
    }
}
