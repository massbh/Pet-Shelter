<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionRequestController;
use App\Models\Pet;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', [ContactUsController::class, 'contactFormSubmit'])
    ->name('contact.submit')
    ->middleware( 'throttle:3,1'); //Only 3 contact forms per minutes just in case

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/qa', function () {
    return view('qa');
});

Route::get('/terms', function () {
    return view('terms-conditions');
})->name('terms');

// Public chart data (available pets only)
Route::get('/api/charts/available-pets-species', [ChartController::class, 'availablePetsBySpecies']);
Route::get('/api/charts/available-pets-age', [ChartController::class, 'petsByAge']);

// Authentication routes
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/signin', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/api/pets', [PetController::class, 'getPetsJson'])->name('api.pets');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Adoption requests - for logged-in users
    Route::get('/adoption-requests/my-requests', [AdoptionRequestController::class, 'myRequests'])->name('adoption-requests.my-requests');
    Route::get('/pets/{pet}/adopt', [AdoptionRequestController::class, 'create'])->name('adoption-requests.create');
    Route::post('/adoption-requests', [AdoptionRequestController::class, 'store'])->name('adoption-requests.store');
    Route::get('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'show'])->name('adoption-requests.show');
    Route::delete('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'destroy'])->name('adoption-requests.destroy');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Pet management
    Route::get('admin/create', [PetController::class, 'create'])->name('pets.create');
    Route::get('/pet/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');

    // Admin-only pet gallery view
    Route::get('/admin/pet-gallery', function () {
        return view('pets.gallery');
    })->name('pets.gallery');

    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
    
    // Adoption request management
    Route::get('/admin/adoption-requests', [AdoptionRequestController::class, 'index'])->name('admin.adoption-requests.index');
    Route::put('/admin/adoption-requests/{adoptionRequest}/status', [AdoptionRequestController::class, 'updateStatus'])->name('admin.adoption-requests.update-status');

    // Chart builder
    Route::get('/admin/chart-builder', [ChartController::class, 'builder'])->name('charts.builder');
    
    // Chart data endpoints
    Route::get('/api/charts/pets-species', [ChartController::class, 'petsBySpecies']);
    Route::get('/api/charts/pets-status', [ChartController::class, 'petsByStatus']);
    Route::get('/api/charts/pets-age', [ChartController::class, 'petsByAge']);
    Route::get('/api/charts/pets-gender', [ChartController::class, 'petsByGender']);
    Route::get('/api/charts/adoption-requests-status', [ChartController::class, 'adoptionRequestsByStatus']);
    Route::get('/api/charts/adoption-requests-month', [ChartController::class, 'adoptionRequestsByMonth']);
    Route::get('/api/charts/most-requested-pets', [ChartController::class, 'mostRequestedPets']);
    
    // New chart data endpoints
    Route::get('/api/charts/average-age-by-species', [ChartController::class, 'averageAgeBySpecies']);
    Route::get('/api/charts/gender-distribution-by-species', [ChartController::class, 'genderDistributionBySpecies']);
    Route::get('/api/charts/newest-pets', [ChartController::class, 'newestPets']);
    Route::get('/api/charts/oldest-pets', [ChartController::class, 'oldestPets']);
    Route::get('/api/charts/pets-created-by-month', [ChartController::class, 'petsCreatedByMonth']);
    Route::get('/api/charts/requests-by-user', [ChartController::class, 'requestsByUser']);
    Route::get('/api/charts/most-requested-species', [ChartController::class, 'mostRequestedSpecies']);
    Route::get('/api/charts/most-requested-age-groups', [ChartController::class, 'mostRequestedAgeGroups']);
    Route::get('/api/charts/user-registrations-over-time', [ChartController::class, 'userRegistrationsOverTime']);
    Route::get('/api/charts/seasonal-trends', [ChartController::class, 'seasonalTrends']);
});

// Chart API routes (these should be separate from the builder route)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/charts', [ChartController::class, 'store'])->name('charts.store');
    Route::get('/api/charts/saved', [ChartController::class, 'index'])->name('charts.index');
    Route::delete('/charts/{id}', [ChartController::class, 'destroy'])->name('charts.destroy');
});

// Legacy route for backwards compatibility - now uses database
Route::get('/pet/{id}', function ($id) {
    $pet = Pet::find($id);
    
    if (!$pet) {
        return redirect('/home')->with('error', 'Pet not found');
    }
    
    // Convert to object with legacy field names for compatibility
    $petData = (object)[
        'id' => $pet->id,
        'name' => $pet->name,
        'species' => $pet->species,
        'age' => $pet->age,
        'sex' => $pet->sex,
        'imageUrl' => $pet->image_url,
        'description' => $pet->description,
    ];
    
    return view('pet', ['pet' => $petData]);
})->name('pet.detail');
