<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;  
use App\Models\AdoptionRequest;  
use App\Models\User;  
use App\Models\Pet;  

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdoptionRequest>
 */
class AdoptionRequestFactory extends Factory
{
    protected $model = AdoptionRequest::class; 

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()  
    {  
        return [  
            'user_id' => User::factory(),  
            'pet_id' => Pet::factory(),  
            'name' => $this->faker->name(),  
            'email' => $this->faker->email(),  
            'visitDate' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),  
            'visitTime' => $this->faker->randomElement(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00']),  
            'message' => $this->faker->sentence(10),  
            'status' => 'pending',  
            'admin_notes' => null,  
        ];  
    } 
}
