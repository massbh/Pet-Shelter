<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreatePetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_pet_with_valid_data(): void
    {
        
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $payload = [
            'name' => 'Buddy',
            'species' => 'Dog',
            'age' => 4,
            'sex' => 'Male',
            'description' => 'Friendly and calm dog.',
            'status' => 'available',
            'image' => UploadedFile::fake()->create('buddy.jpg', 100, 'image/jpeg'),

        ];

        $response = $this->post('/pets', $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('pets', [
            'name' => 'Buddy',
            'species' => 'Dog',
            'age' => 4,
            'sex' => 'Male',
            'status' => 'available',
        ]);
    }
    public function test_invalid_pet_data_is_rejected(): void
{
    Storage::fake('public');

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin2@example.com',
        'password' => 'password123',
        'role' => 'admin',
    ]);

    $this->actingAs($admin);

    $payload = [
        'name' => '',                 
        'species' => 'Dog',
        'age' => -1,                
        'sex' => 'Male',
        'description' => 'Test',
        'status' => 'available',
        'image' => \Illuminate\Http\UploadedFile::fake()->create('buddy.jpg', 100, 'image/jpeg'),
    ];

    $response = $this->from('/admin/create')->post('/pets', $payload);

    $response->assertRedirect();
    $response->assertSessionHasErrors(); 

    $this->assertDatabaseMissing('pets', [
        'species' => 'Dog',
        'age' => -1,
    ]);
}

}
