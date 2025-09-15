// In tests/Feature/Auth/RegistrationTest.php

// You can comment out or remove these tests if your app doesn't have public registration.
// If you do, you'll need to modify them to handle company creation.

// test('registration screen can be rendered', function () {
//     $response = $this->get('/register');
//     $response->assertStatus(200);
// });

// test('new users can register', function () {
//     $response = $this->post('/register', [
//         'name' => 'Test User',
//         'email' => 'test@example.com',
//         'password' => 'password',
//         'password_confirmation' => 'password',
//     ]);
//     $this->assertAuthenticated();
//     $response->assertRedirect(RouteServiceProvider::HOME);
// });