<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Dashboard bergantung pada modul Pendaftaran; untuk test auth pakai stub.
        Route::middleware('auth')->get('dashboard', fn () => 'dashboard')->name('dashboard');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Daftar');
    }

    public function test_users_can_register_and_are_logged_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'Budi@Example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::firstWhere('email', 'budi@example.com');

        $this->assertNotNull($user, 'Email seharusnya dinormalisasi menjadi lowercase.');
        $this->assertSame('user', $user->role->value);
        $this->assertNotSame('password', $user->password, 'Password harus ter-hash.');
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'sama@example.com']);

        $this->post(route('register.store'), [
            'name' => 'Pengguna Lain',
            'email' => 'SAMA@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_registration_rejects_mismatched_password_confirmation(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password-beda',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_users_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['email' => 'budi@example.com']);

        $response = $this->post(route('login.store'), [
            'email' => '  BUDI@Example.com  ',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_users_cannot_login_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'password-salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.store'), [
                'email' => 'budi@example.com',
                'password' => 'password-salah',
            ]);
        }

        $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_guests_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_cannot_access_login_screen(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect();
    }

    public function test_root_redirects_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }

    public function test_dashboard_controller_is_invokable(): void
    {
        $this->assertTrue(
            method_exists(DashboardController::class, '__invoke'),
            'DashboardController harus invokable karena route memakai class langsung.'
        );
    }
}
