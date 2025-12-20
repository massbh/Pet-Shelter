<?php

namespace Tests\Feature\AdoptionRequests;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreAdoptionRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_cannot_submit_incomplete_adoption_request(): void
    {

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        $pet = Pet::create([
            'name' => 'Max',
            'species' => 'Dog',
            'age' => 3,
            'sex' => 'Male',
            'description' => 'Friendly dog',
            'status' => 'available',
        ]);

        $payload = [
            'pet_id' => $pet->id,
            'message' => 'I would like to adopt this pet.',
        ];

        $response = $this->from("/pets/{$pet->id}/adopt")
            ->post('/adoption-requests', $payload);

        $response->assertRedirect("/pets/{$pet->id}/adopt");
        $response->assertSessionHasErrors(['name']);

        
        $this->assertDatabaseMissing('adoption_requests', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }
}
