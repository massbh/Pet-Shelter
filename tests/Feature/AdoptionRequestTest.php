<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;

class AdoptionRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Users can submit adoption requests
     */

    public function test_users_can_submit_adoption_request()  
    {  
        $user = User::factory()->create(['role' => 'admin']);  
        $pet = Pet::factory()->create(['status' => 'available']);  
        
        $response = $this->actingAs($user)  
            ->post('/contact', [  
                'name' => $user->name,  
                'email' => $user->email,  
                'subject' => 'adoption',  
                'message' => 'I want to adopt this pet',  
                'visitDate' => now()->addDays(3)->format('Y-m-d'),  
                'visitTime' => '10:00 AM',  
                'petInterest' => $pet->name  
            ]);  
        
        $response->assertJson([  
            'success' => true,  
            'message' => 'Your adoption request has been submitted!'  
        ]);  
        
        $this->assertDatabaseHas('adoption_requests', [  
            'user_id' => $user->id,  
            'pet_id' => $pet->id,  
            'status' => 'pending'  
        ]);  
    }

    /**
     * Test: Non-logged-in users cannot make adoption requests
     */
    public function test_guest_cannot_submit_adoption_request()  
    {  
        $pet = Pet::factory()->create(['status' => 'available']);  
          
        $response = $this->post('/contact', [  
            'name' => 'Test User',  
            'email' => 'test@example.com',  
            'subject' => 'adoption',  
            'message' => 'I want to adopt this pet',  
            'visitDate' => now()->addDays(3)->format('Y-m-d'),  
            'visitTime' => '10:00 AM',  
            'petInterest' => $pet->name  
        ]);  
          
        // Fail because auth()->id() returns null for guests  
        $response->assertJson([  
            'success' => false,  
            'message' => 'An error occurred. Please try again.'  
        ]);  
        $this->assertDatabaseEmpty('adoption_requests');

    } 

    /**
     * Test: Admin can create adoption requests
     */
    public function test_admin_can_submit_adoption_request()  
    {  
        $admin = User::factory()->create(['role' => 'admin']);  
        $pet = Pet::factory()->create(['status' => 'available']);  
          
        $response = $this->actingAs($admin)  
            ->post('/contact', [  
                'name' => $admin->name,  
                'email' => $admin->email,  
                'subject' => 'adoption',  
                'message' => 'I want to adopt this pet',  
                'visitDate' => now()->addDays(3)->format('Y-m-d'),  
                'visitTime' => '10:00 AM',  
                'petInterest' => $pet->name  
            ]);  
          
        $response->assertJson([  
            'success' => true,  
            'message' => 'Your adoption request has been submitted!'  
        ]);  
          
        $this->assertDatabaseHas('adoption_requests', [  
            'user_id' => $admin->id,  
            'pet_id' => $pet->id,  
            'status' => 'pending'  
        ]);  
    }

    /**
     * Test: No pet_id or pet_name causes adoption request to fail
     */
    public function test_adoption_request_with_no_pet_id_fails()  
    {  
        $user = User::factory()->create(['role' => 'user']);  
          
        $response = $this->actingAs($user)  
            ->post('/contact', [  
                'name' => $user->name,  
                'email' => $user->email,  
                'subject' => 'adoption',  
                'message' => 'I want to adopt a pet',  
                'visitDate' => now()->addDays(3)->format('Y-m-d'),  
                'visitTime' => '10:00 AM',  
                'petInterest' => ''  
            ]);  
          
            
        $response->assertJson([  
            'success' => false,  
            'message' => 'An error occurred. Please try again.'  
        ]);  
        
        $this->assertDatabaseEmpty('adoption_requests');
    }

    /**
     * Test: Admins can approve adoption requests
     */
    public function test_admin_can_approve_adoption_request()  
    {  
        $admin = User::factory()->create(['role' => 'admin']);  
        $request = AdoptionRequest::factory()->create(['status' => 'pending']);  
          
        $response = $this->actingAs($admin)  
            ->put("/admin/adoption-requests/{$request->id}/status", [  
                'status' => 'approved',  
                'admin_notes' => 'Approved for adoption'  
            ]);  
          
        $response->assertRedirect('/dashboard');  
        $this->assertDatabaseHas('adoption_requests', [  
            'id' => $request->id,  
            'status' => 'approved',  
            'admin_notes' => 'Approved for adoption'  
        ]);  
        $this->assertDatabaseHas('pets', [  
            'id' => $request->pet_id,  
            'status' => 'adopted'  
        ]);  
    } 

    /**
     * Test: Admins can reject adoption requests
     */
    public function test_admin_can_reject_adoptions_request()  
    {  
        $admin = User::factory()->create(['role' => 'admin']);  
        $request = AdoptionRequest::factory()->create(['status' => 'pending']);  
          
        $response = $this->actingAs($admin)  
            ->put("/admin/adoption-requests/{$request->id}/status", [  
                'status' => 'rejected',  
                'admin_notes' => 'Not suitable for adoption'  
            ]);  
          
        $response->assertRedirect('/dashboard');  
        $this->assertDatabaseHas('adoption_requests', [  
            'id' => $request->id,  
            'status' => 'rejected',  
            'admin_notes' => 'Not suitable for adoption'  
        ]);  
        $this->assertDatabaseHas('pets', [  
            'id' => $request->pet_id,  
            'status' => 'available'  
        ]);  
    } 

    /**
     * Test: User cannot approve adoption requests
     */
    public function test_user_cannot_approve_reject_requests()  
    {  
        $user = User::factory()->create(['role' => 'user']);  
        $request = AdoptionRequest::factory()->create(['status' => 'pending']);  
          
        $response = $this->actingAs($user)  
            ->put("/admin/adoption-requests/{$request->id}/status", [  
                'status' => 'approved'  
            ]);  
          
        $response->assertForbidden();  
        $this->assertDatabaseHas('adoption_requests', [  
            'id' => $request->id,  
            'status' => 'pending'  
        ]);  
    } 

    /**
     * Test: User cannot reject adoption requests
     */
    public function test_guest_cannot_approve_reject_requests()  
    {  
        $request = AdoptionRequest::factory()->create(['status' => 'pending']);  
          
        $response = $this->put("/admin/adoption-requests/{$request->id}/status", [  
            'status' => 'approved'  
        ]);  
          
        $response->assertRedirect('/signin');  
    } 
    
    
}