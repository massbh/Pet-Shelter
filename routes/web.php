<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\BlogController;
use App\Models\Pet;
use Illuminate\Support\Facades\Route;
use App\Models\QuizQuestion;

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

Route::get('/api/quiz/questions', function () {
    return QuizQuestion::where('is_active', true)
        ->orderBy('order')
        ->get();
});

// Blog public routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

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
    
    // Protected with ownership verification middleware
    Route::get('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'show'])
        ->middleware('verify.adoption.owner')
        ->name('adoption-requests.show');
    Route::delete('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'destroy'])
        ->middleware('verify.adoption.owner')
        ->name('adoption-requests.destroy');
});


Route::get('/quiz', [AuthController::class, 'quiz'])->name('quiz');


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
    
    // Blog management - Admin only
    Route::get('/admin/blog', [BlogController::class, 'adminIndex'])->name('admin.blog.index');
    Route::get('/admin/blog/create', [BlogController::class, 'create'])->name('admin.blog.create');
    Route::post('/admin/blog', [BlogController::class, 'store'])->name('admin.blog.store');
    Route::get('/admin/blog/{post}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/admin/blog/{post}', [BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/{post}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');
});

// Legacy route for backwards compatibility
Route::get('/pet/{id}', function ($id) {
    // Validate ID is numeric to prevent injection
    if (!is_numeric($id)) {
        abort(404);
    }
    
    $pet = Pet::find((int)$id);
    
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