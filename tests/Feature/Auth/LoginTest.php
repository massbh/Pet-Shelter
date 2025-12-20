<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials_succeeds(): void
{
    $plainPassword = 'password123';

    $user = User::create([
        'name' => 'Test User',
        'email' => 'testuser@gmail.com',
        'password' => $plainPassword,   
        'role' => 'user',
    ]);

    $response = $this->from('/signin')->post('/signin', [
        'email' => $user->email,
        'password' => $plainPassword,
    ]);


    $response->assertSessionHasNoErrors();


    $response->assertSessionDoesntHaveErrors(['email']);


    $response->assertRedirect();

    $this->assertTrue(Auth::check(), 'Auth::check() is false after login request.');
    $this->assertAuthenticatedAs($user);
}

}
