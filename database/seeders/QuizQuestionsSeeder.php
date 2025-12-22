<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;

class QuizQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        QuizQuestion::truncate();
        $questions = [
            [
                'question' => 'How much time can you dedicate to your pet daily?',
                'options' => [
                    [
                        'text' => 'A lot of time (3+ hours) - I love being active',
                        'traits' => ['energy' => 3, 'maintenance' => 3, 'playful' => 2]
                    ],
                    [
                        'text' => 'Moderate time (1-2 hours)',
                        'traits' => ['energy' => 2, 'maintenance' => 2]
                    ],
                    [
                        'text' => 'Limited time (less than 1 hour)',
                        'traits' => ['energy' => 1, 'maintenance' => 1, 'independent' => 2]
                    ]
                ],
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What type of personality do you prefer?',
                'options' => [
                    [
                        'text' => 'Playful and energetic',
                        'traits' => ['playful' => 3, 'energy' => 3, 'social' => 2]
                    ],
                    [
                        'text' => 'Balanced',
                        'traits' => ['playful' => 2, 'energy' => 2, 'calm' => 2]
                    ],
                    [
                        'text' => 'Calm and relaxed',
                        'traits' => ['calm' => 3, 'energy' => 1, 'independent' => 2]
                    ]
                ],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'What type of animal do you prefer?',
                'options' => [
                    [
                        'text' => 'Dog',
                        'traits' => ['species_dog' => 15, 'social' => 2]
                    ],
                    [
                        'text' => 'Cat',
                        'traits' => ['species_cat' => 15, 'independent' => 1]
                    ],
                    [
                        'text' => 'Exotic animal (reptile, bird, rabbit, capybara)',
                        'traits' => ['species_exotic' => 15, 'maintenance' => 2]
                    ],
                    [
                        'text' => 'No preference',
                        'traits' => []
                    ]
                ],
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Do you prefer a young or adult animal?',
                'options' => [
                    [
                        'text' => 'Young (0-3 years) - Full of energy',
                        'traits' => ['age_young' => 3, 'energy' => 2, 'playful' => 2]
                    ],
                    [
                        'text' => 'Adult (4-8 years) - Balanced',
                        'traits' => ['age_adult' => 3]
                    ],
                    [
                        'text' => 'Senior (9+ years) - More relaxed',
                        'traits' => ['age_senior' => 3, 'calm' => 2, 'energy' => -1]
                    ]
                ],
                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Your home is more...',
                'options' => [
                    [
                        'text' => 'Active and lively',
                        'traits' => ['social' => 3, 'energy' => 2]
                    ],
                    [
                        'text' => 'Quiet and peaceful',
                        'traits' => ['calm' => 3, 'independent' => 2]
                    ]
                ],
                'order' => 5,
                'is_active' => true,
            ],
            [
                'question' => "What's your living space like?",
                'options' => [
                    [
                        'text' => 'House with yard',
                        'traits' => ['species_dog' => 2, 'energy' => 2]
                    ],
                    [
                        'text' => 'Apartment with some space',
                        'traits' => ['species_cat' => 1, 'maintenance' => 1]
                    ],
                    [
                        'text' => 'Small apartment',
                        'traits' => ['species_cat' => 2, 'species_exotic' => 2, 'maintenance' => -1]
                    ]
                ],
                'order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'How important is social interaction with your pet?',
                'options' => [
                    [
                        'text' => 'Very important - I want a best friend',
                        'traits' => ['social' => 4, 'species_dog' => 2, 'playful' => 2]
                    ],
                    [
                        'text' => 'Somewhat important - Nice to have',
                        'traits' => ['social' => 2, 'independent' => 1]
                    ],
                    [
                        'text' => 'Not very important - I prefer independent pets',
                        'traits' => ['independent' => 3, 'species_cat' => 1, 'species_exotic' => 2]
                    ]
                ],
                'order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'How do you feel about training and commands?',
                'options' => [
                    [
                        'text' => 'I love it! I want to teach tricks and commands',
                        'traits' => ['species_dog' => 2, 'playful' => 2, 'energy' => 2, 'maintenance' => 2]
                    ],
                    [
                        'text' => 'Basic training is fine',
                        'traits' => ['maintenance' => 1]
                    ],
                    [
                        'text' => 'I prefer a pet that needs minimal training',
                        'traits' => ['independent' => 2, 'species_cat' => 1, 'species_exotic' => 2, 'maintenance' => -1]
                    ]
                ],
                'order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'What kind of activities do you enjoy?',
                'options' => [
                    [
                        'text' => 'Outdoor adventures (hiking, running, walking)',
                        'traits' => ['energy' => 3, 'species_dog' => 3, 'playful' => 2]
                    ],
                    [
                        'text' => 'Indoor activities (playing, toys, games)',
                        'traits' => ['playful' => 3, 'species_cat' => 1, 'social' => 2]
                    ],
                    [
                        'text' => 'Relaxing at home (reading, watching TV)',
                        'traits' => ['calm' => 3, 'independent' => 2, 'species_cat' => 1]
                    ],
                    [
                        'text' => 'Observing and quiet hobbies',
                        'traits' => ['calm' => 3, 'species_exotic' => 3, 'independent' => 2]
                    ]
                ],
                'order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Do you have other pets at home?',
                'options' => [
                    [
                        'text' => 'Yes, and I want a pet that gets along with others',
                        'traits' => ['social' => 3, 'species_dog' => 2, 'playful' => 1]
                    ],
                    [
                        'text' => 'Yes, but they can adapt',
                        'traits' => ['social' => 1]
                    ],
                    [
                        'text' => 'No, and I want a single pet',
                        'traits' => ['independent' => 2]
                    ]
                ],
                'order' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'How much grooming and maintenance are you willing to do?',
                'options' => [
                    [
                        'text' => 'A lot - I enjoy grooming and care routines',
                        'traits' => ['maintenance' => 3, 'species_dog' => 1]
                    ],
                    [
                        'text' => 'Moderate - Regular but not excessive',
                        'traits' => ['maintenance' => 2]
                    ],
                    [
                        'text' => 'Minimal - I want low maintenance',
                        'traits' => ['maintenance' => -2, 'species_cat' => 1, 'species_exotic' => 2, 'independent' => 1]
                    ]
                ],
                'order' => 11,
                'is_active' => true,
            ],
            [
                'question' => 'What noise level are you comfortable with?',
                'options' => [
                    [
                        'text' => 'I love vocal pets - barking, meowing, chirping',
                        'traits' => ['social' => 2, 'species_dog' => 2, 'energy' => 1]
                    ],
                    [
                        'text' => 'Some noise is okay',
                        'traits' => []
                    ],
                    [
                        'text' => 'I prefer quiet pets',
                        'traits' => ['calm' => 2, 'species_cat' => 1, 'species_exotic' => 2, 'independent' => 1]
                    ]
                ],
                'order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($questions as $question) {
            QuizQuestion::create($question);
        }
    }
}