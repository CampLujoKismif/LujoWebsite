<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_as_parent(): void
    {
        // Create the parent role
        $parentRole = Role::create([
            'name' => 'parent',
            'display_name' => 'Parent',
            'description' => 'Parent with access to their children\'s information',
            'is_admin' => false,
        ]);

        $response = Volt::test('auth.register')
            ->set('name', 'Test Parent')
            ->set('email', 'parent@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('user_type', 'parent')
            ->call('register');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard.home', absolute: false));

        $this->assertAuthenticated();
        
        $user = auth()->user();
        $this->assertTrue($user->hasRole('parent'));
    }

    public function test_new_users_can_register_as_camper(): void
    {
        // Create the camper role
        $camperRole = Role::create([
            'name' => 'camper',
            'display_name' => 'Camper',
            'description' => 'Camper with limited personal access',
            'is_admin' => false,
        ]);

        $response = Volt::test('auth.register')
            ->set('name', 'Test Camper')
            ->set('email', 'camper@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('user_type', 'camper')
            ->call('register');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard.home', absolute: false));

        $this->assertAuthenticated();
        
        $user = auth()->user();
        $this->assertTrue($user->hasRole('camper'));
    }

    public function test_registration_requires_user_type(): void
    {
        $response = Volt::test('auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('user_type', '')
            ->call('register');

        $response->assertHasErrors(['user_type']);
    }

    public function test_registration_validates_user_type_values(): void
    {
        $response = Volt::test('auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('user_type', 'invalid_type')
            ->call('register');

        $response->assertHasErrors(['user_type']);
    }
}
