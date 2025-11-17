<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
                'name' => $jane->name,
                'email' => $jane->email,
                'visitDate' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'visitTime' => '10:00',
                'message' => 'I have experience with dogs and would love to adopt Max. I have a large backyard and plenty of time for walks.',
                'status' => 'pending',
            ]);

            AdoptionRequest::create([
                'user_id' => $jane->id,
                'pet_id' => $pets[1]['id'] ?? Pet::skip(1)->first()->id,
                'name' => $jane->name,
                'email' => $jane->email,
                'visitDate' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'visitTime' => '14:00',
                'message' => 'I am looking for a calm companion. Luna seems perfect for my apartment.',
                'status' => 'approved',
                'admin_notes' => 'Approved - Great fit for Luna! Home visit scheduled.',
            ]);

            // Create adoption requests for John
            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[2]['id'] ?? Pet::skip(2)->first()->id,
                'name' => $john->name,
                'email' => $john->email,
                'visitDate' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'visitTime' => '11:00',
                'message' => 'I have two children and looking for a family-friendly dog. Charlie would be perfect for our family.',
                'status' => 'pending',
            ]);

            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[3]['id'] ?? Pet::skip(3)->first()->id,
                'name' => $john->name,
                'email' => $john->email,
                'visitDate' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'visitTime' => '09:00',
                'message' => 'I want to adopt Bella for my daughter. We have experience with cats.',
                'status' => 'rejected',
                'admin_notes' => 'Unfortunately, we need more information about your living situation and references.',
            ]);

            AdoptionRequest::create([
                'user_id' => $john->id,
                'pet_id' => $pets[4]['id'] ?? Pet::skip(4)->first()->id,
                'name' => $john->name,
                'email' => $john->email,
                'visitDate' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'visitTime' => '15:00',
                'message' => 'Milo seems like a great cat for my lifestyle. I like to stay at home watching tv shows.',
                'status' => 'pending',
            ]);

            // Create an adoption request without a specific pet (general interest)
            AdoptionRequest::create([
                'user_id' => $jane->id,
                'pet_id' => $pets[5]['id'] ?? Pet::skip(4)->first()->id,
                'name' => $jane->name,
                'email' => $jane->email,
                'visitDate' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'visitTime' => '13:00',
                'message' => 'I would like to visit the shelter to see all available cats. I am open to adopting any friendly cat.',
                'status' => 'pending',
            ]);
        }

        $this->command->info('Adoption requests seeded successfully with visit dates and times!');
    }
}
