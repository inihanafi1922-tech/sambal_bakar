<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_password_page(): void
    {
        $this->get('/password')->assertRedirect('/login');
    }

    public function test_user_can_open_password_page(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($user)->get('/password')->assertOk();
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($user)
            ->from('/password')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect('/password')
            ->assertSessionHas('status');

        $this->assertTrue(Hash::check('password-baru', $user->fresh()->password));
    }

    public function test_password_change_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($user)
            ->from('/password')
            ->put('/password', [
                'current_password' => 'salah',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect('/password')
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_password_change_requires_matching_confirmation(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($user)
            ->from('/password')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'password-baru',
                'password_confirmation' => 'beda',
            ])
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
