<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Support\Facades\Hash;

class AdoptionRequestSeeder extends Seeder
{
    public function run(): void
    {
        $jane = User::firstOrCreate(
            ['email' => 'jane@happinest.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );

        $john = User::firstOrCreate(
            ['email' => 'john@happinest.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );

        $pets = [];
        
        if (Pet::count() === 0) {
            $pets[] = Pet::create([
                'name' => 'Max',
                'species' => 'Dog',
                'age' => 3,
                'sex' => 'Male',
                'description' => 'Friendly and energetic dog',
                'status' => 'available',
            ]);

            $pets[] = Pet::create([
                'name' => 'Luna',
                'species' => 'Cat',
                'age' => 2,
                'sex' => 'Female',
                'description' => 'Calm and affectionate cat',
                'status' => 'available',
            ]);

            $pets[] = Pet::create([
                'name' => 'Charlie',
                'species' => 'Dog',
                'age' => 5,
                'sex' => 'Male',
                'description' => 'Well-trained and loyal dog',
                'status' => 'available',
            ]);

            $pets[] = Pet::create([
                'name' => 'Bella',
                'species' => 'Cat',
                'age' => 1,
                'sex' => 'Female',
                'description' => 'Playful and curious kitten',
                'status' => 'available',
            ]);

            $pets[] = Pet::create([
                'name' => 'Rocky',
                'species' => 'Dog',
                'age' => 4,
                'sex' => 'Male',
                'description' => 'Active and protective dog',
                'status' => 'available',
            ]);
        } else {
            $pets = Pet::where('status', 'available')->take(5)->get()->toArray();
        }

        // Create adoption requests for Jane
        if (count($pets) > 0) {
            AdoptionRequest::create([
                'user_id' => $jane->id,
                'pet_id' => $pets[0]['id'] ?? Pet::first()->id,
                'message' => 'I have experience with turtles and would love to adopt Nora.',
                'status' => 'pending',
            ]);

            AdoptionRequest::create([
                'user_id' => $jane->id,
                'pet_id' => $pets[1]['id'] ?? Pet::skip(1)->first()->id,
                'message' => 'I am looking for a calm companion. Milo seems perfect for my apartment.',
                'status' => 'approved',
                'admin_notes' => 'Approved - Great fit for Milo!',
            ]);

            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[2]['id'] ?? Pet::skip(2)->first()->id,
                'message' => 'I have two children and looking for a family-friendly dog. Bella would be perfect.',
                'status' => 'pending',
            ]);

            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[3]['id'] ?? Pet::skip(3)->first()->id,
                'message' => 'I want to adopt Simba for my daughter.',
                'status' => 'rejected',
                'admin_notes' => 'Unfortunately, we need more information about your living situation.',
            ]);

            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[4]['id'] ?? Pet::skip(4)->first()->id,
                'message' => 'Daisy seems like a great flat dog.',
                'status' => 'pending',
            ]);
        }

        $this->command->info('Adoption requests seeded successfully!');
    }
}
