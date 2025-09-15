<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Database\Factories\CompanyFactory;
use Tests\TestCase;

test('a user can log in with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

test('a guest cannot access the dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('an authenticated user can access the dashboard', function () {
    $company = \App\Models\Company::factory()->create();
    $user = \App\Models\User::factory()->create([
        'company_id' => $company->id, 
        'role' => 'company' 
    ]);

    $this->actingAs($user)
         ->get('/dashboard')
         ->assertOk();
});