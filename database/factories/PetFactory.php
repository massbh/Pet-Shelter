<?php

namespace Database\Factories;
use App\Models\Pet;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class; 

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */ 
    public function definition()  
    {  
        return [  
            'name' => $this->faker->firstName(),  
            'species' => $this->faker->randomElement(['Dog', 'Cat', 'Rabbit', 'Bird', 'Hamster']),  
            'age' => $this->faker->numberBetween(0, 15),  
            'sex' => $this->faker->randomElement(['Male', 'Female']),  
            'description' => $this->faker->sentence(10),  
            'image_url' => '/storage/pets/' . $this->faker->uuid() . '.jpg',  
            'status' => 'available',  
        ];  
    } 
}
