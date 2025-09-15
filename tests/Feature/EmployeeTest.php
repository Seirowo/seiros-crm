<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Employee; 
use Database\Factories\CompanyFactory; 
use Database\Factories\EmployeeFactory; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


test('an admin can view the employees list', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get('/employees')
         ->assertOk()
         ->assertSee('Employees');
});

test('an admin can create an employee', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $company = Company::factory()->create(); 

    $this->actingAs($admin)->post('/employees', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '1234567890',
        'company_id' => $company->id, 
    ])->assertRedirect('/employees');

    $this->assertDatabaseHas('employees', ['email' => 'john@example.com']);
});

test('a company user can view their own employees', function () {
    $company = Company::factory()->create();
    $companyUser = User::factory()->create(['role' => 'company', 'company_id' => $company->id]);
    $employee = Employee::factory()->create(['company_id' => $company->id, 'name' => 'Employee A']);

    $this->actingAs($companyUser)->get('/employees')
         ->assertOk()
         ->assertSeeText($employee->name); 
});

test('a company user cannot create an employee for another company', function () {
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();
    $companyAUser = User::factory()->create(['company_id' => $companyA->id]);

    $this->actingAs($companyAUser);

    $response = $this->post(route('employees.store'), [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'phone' => '0987654321',
        'company_id' => $companyB->id,
    ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('employees', ['email' => 'jane@example.com']);
});

test('an employee can be deleted', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();

    $this->actingAs($admin)->delete("/employees/{$employee->id}")
         ->assertRedirect('/employees');

    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
});