<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@happinest.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@happinest.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@happinest.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Guest User',
            'email' => 'guest@happinest.com',
            'password' => Hash::make('password123'),
            'role' => 'guest',
        ]);

        $jsonPath = public_path('assets/animals.json');
        
        if (file_exists($jsonPath)) {
            $animals = json_decode(file_get_contents($jsonPath), true);
            
            foreach ($animals as $animal) {
                Pet::create([
                    'name' => $animal['name'],
                    'species' => $animal['species'],
                    'age' => $animal['age'],
                    'sex' => $animal['sex'],
                    'image_url' => $animal['imageUrl'] ?? null,
                    'description' => $animal['description'] ?? null,
                    'status' => 'available',
                ]);
            }
            
            $this->command->info('Pets imported from JSON successfully!');
        } else {
            $this->command->warn('Animals JSON file not found. Creating sample pets...');
            
            // Create sample pets if JSON doesn't exist
            Pet::create([
                'name' => 'Max',
                'species' => 'Dog',
                'age' => 3,
                'sex' => 'Male',
                'description' => 'Friendly and energetic dog looking for a loving home.',
                'status' => 'available',
            ]);

            Pet::create([
                'name' => 'Whiskers',
                'species' => 'Cat',
                'age' => 2,
                'sex' => 'Female',
                'description' => 'Calm and affectionate cat who loves to cuddle.',
                'status' => 'available',
            ]);
        }

        $this->command->info('Database seeded successfully!');
    }
}
