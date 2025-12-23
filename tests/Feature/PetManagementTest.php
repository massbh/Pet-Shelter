<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pet;

class PetManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Admins can create pets
     */
    public function test_admin_can_create_pets(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);  
      
        $petData = [  
            'name' => 'Test Dog',  
            'species' => 'Dog',  
            'age' => 3,  
            'sex' => 'Male',  
            'description' => 'Test description'  
        ];  
        
        $response = $this->actingAs($admin)
            ->post('/pets', $petData);  
        
        $response->assertRedirect('/admin/pet-gallery');  
        $this->assertDatabaseHas('pets', $petData);
    }

    /**
     * Test: Regular users cannot create pets
     */
    public function test_non_admin_cannot_create_pets(): void
    {
        $user = User::factory()->create(['role' => 'user']);  
      
        $petData = [  
            'name' => 'Test Dog',  
            'species' => 'Dog',  
            'age' => 3,  
            'sex' => 'Male',  
            'description' => 'Test description'  
        ];  
        
        $response = $this->actingAs($user)
            ->post('/pets', $petData);  

        $response->assertStatus(403);
        $this->assertDatabaseMissing('pets', $petData);
    }

    /**
     * Test: Non-logged in users cannot create pets, it requires authentication
     */
    public function test_pet_creation_requires_authentication()  
    {  
        $petData = [  
            'name' => 'Test Dog',  
            'species' => 'Dog',  
            'age' => 3,  
            'sex' => 'Male',  
            'description' => 'Test description'  
        ];  
        
        $response = $this->post('/pets', $petData);  
        $response->assertRedirect('/signin');  

    }

    /**
     * Test: Admins can update all pet information
     */
    public function test_admin_can_update_pet_all_information()  
    {  
        $admin = User::factory()->create(['role' => 'admin']);  
        $pet = Pet::factory()->create(['name' => 'Original Name']);  
          
        $updateData = [  
            'name' => 'Updated Name',  
            'species' => 'Cat',  
            'age' => 5,  
            'sex' => 'Female',  
            'description' => 'Updated description',  
            'status' => 'adopted'  
        ];  
          
        $response = $this->actingAs($admin)
            ->put("/pets/{$pet->id}", $updateData);  
          
        $response->assertRedirect('/admin/pet-gallery');  
        $this->assertDatabaseHas('pets', array_merge(['id' => $pet->id], $updateData));  
    } 

    /**
     * Test: Non-admin users cannot update pet information
     */
    public function test_user_cannot_update_pet()  
    {  
        $user = User::factory()->create(['role' => 'user']);  
        $pet = Pet::factory()->create(['name' => 'test pet']);  

        $updateData = [  
            'name' => 'Updated Name',  
            'species' => 'Cat',  
            'age' => 5,  
            'sex' => 'Female',  
            'description' => 'Updated description',  
            'status' => 'adopted'  
        ]; 
          
        $response = $this->actingAs($user)->put("/pets/{$pet->id}", $updateData);  
          
        $response->assertForbidden();  
        $this->assertDatabaseMissing('pets', ['name' => 'Updated Name']);  
        $this->assertDatabasehas('pets', ['name' => 'test pet']);  
    }  
    
    /**
     * Test: Non logged-in users cannot update pet information
     */
    public function test_guest_cannot_update_pet()  
    {  
        $pet = Pet::factory()->create(['name' => 'test pet']);  

        $updateData = [  
            'name' => 'Updated Name',  
            'species' => 'Cat',  
            'age' => 5,  
            'sex' => 'Female',  
            'description' => 'Updated description',  
            'status' => 'adopted'  
        ]; 
          
        $response = $this->put("/pets/{$pet->id}", $updateData);  
          
        $response->assertRedirect('/signin'); 
        $this->assertDatabaseMissing('pets', ['name' => 'Updated Name']);  
        $this->assertDatabasehas('pets', ['name' => 'test pet']);  
        
    }

    /**
     * Test: Admins can delete pets
     */
    public function test_admin_can_delete_pets()  
    {  
        $admin = User::factory()->create(['role' => 'admin']);  
        $pet = Pet::factory()->create();  

        // assert pet exists before it is deleted
        $this->assertDatabaseHas('pets', ['id' => $pet->id]);
          

        $response = $this->actingAs($admin)->delete("/pets/{$pet->id}");  
          
        $response->assertRedirect('/admin/pet-gallery');  
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);  
    } 

    /**
     * Test: Non-admin user cannot delete pets
     */
    public function test_user_cannot_delete_pet()  
    {  
        $user = User::factory()->create(['role' => 'user']);  
        $pet = Pet::factory()->create();  
          
        $response = $this->actingAs($user)
            ->delete("/pets/{$pet->id}");  
          
        $response->assertForbidden();  
        $this->assertDatabaseHas('pets', ['id' => $pet->id]);  
    }  
    
    /**
     * Test: Non-logged-in user cannot delete pets
     */
    public function test_guest_cannot_delete_pet()  
    {  
        $pet = Pet::factory()->create();  
          
        $response = $this->delete("/pets/{$pet->id}");  
          
        $response->assertRedirect('/signin');  
        $this->assertDatabaseHas('pets', ['id' => $pet->id]);  
    } 

}