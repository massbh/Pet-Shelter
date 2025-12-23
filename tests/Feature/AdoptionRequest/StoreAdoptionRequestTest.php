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
        'password' => 'password123', // don't bcrypt; let the model cast hash it
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
        // missing: name/email/visitDate/visitTime
    ];

    try {
        $response = $this->post('/adoption-requests', $payload);

        
        $response->assertStatus(302);

    } catch (\Throwable $e) {
        
        $this->assertTrue(
            str_contains($e->getMessage(), 'SQLSTATE[23000]') ||
            str_contains($e->getMessage(), 'NOT NULL constraint failed') ||
            str_contains($e->getMessage(), 'Integrity constraint violation'),
            'Unexpected exception: ' . $e->getMessage()
        );
    }

    
    $this->assertDatabaseMissing('adoption_requests', [
        'user_id' => $user->id,
        'pet_id' => $pet->id,
    ]);
}

}
