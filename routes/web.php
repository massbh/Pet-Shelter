<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/qa', function () {
    return view('qa');
});

Route::get('/signin', function () {
    return view('signin');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/terms', function () {
    return view('terms-conditions');
});

Route::get('/pet/{id}', function ($id) {
    // force JSON reading from public/assets
    $jsonPath = public_path('assets/animals.json');
    
    $animals = json_decode(file_get_contents($jsonPath), true);
    
    // find pet
    $pet = null;
    foreach ($animals as $animal) {
        if (isset($animal['id']) && $animal['id'] == (int)$id) {
            $pet = $animal;
            break;
        }
    }
    
    // prevent 404 :)
    if (!$pet) {
        return redirect('/home')->with('error', 'Pet not found');
    }
    
    // absolute url
    if (isset($pet['imageUrl']) && !str_starts_with($pet['imageUrl'], '/')) {
        $pet['imageUrl'] = '/' . ltrim($pet['imageUrl'], '/');
    }
    
    return view('pet', ['pet' => (object)$pet]);
})->name('pet.detail');