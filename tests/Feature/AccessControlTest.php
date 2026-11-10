<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_opening_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_customer_menu_is_no_longer_available(): void
    {
        $this->get('/menu')->assertNotFound();
    }

    public function test_kasir_can_open_cashier_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->get('/kasir')->assertOk();
    }

    public function test_kasir_cannot_open_financial_report(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->get('/admin/laporan')->assertForbidden();
    }

    public function test_admin_can_open_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_admin_can_open_financial_report(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin/laporan')->assertOk();
    }

    public function test_admin_can_open_cashier_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/kasir')->assertOk();
    }

    public function test_kasir_cannot_open_kitchen_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->get('/kitchen')->assertForbidden();
    }

    public function test_admin_can_open_kitchen_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/kitchen')->assertOk();
    }

    public function test_kasir_can_store_order(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $response = $this->actingAs($kasir)->postJson('/kasir/order', [
            'table_number' => '5',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 28000, 'qty' => 2, 'spicy_level' => 3],
            ],
            'total_amount' => 56000,
            'payment_method' => 'cash',
        ]);

        $response->assertOk()->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'table_number' => '5',
            'total_amount' => 56000,
            'payment_status' => 'pending',
        ]);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_kasir_login_redirects_to_cashier_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->post('/login', [
            'email' => $kasir->email,
            'password' => 'password',
        ])->assertRedirect(route('kasir.index'));

        $this->assertAuthenticatedAs($kasir);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_kasir_can_print_receipt(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $order = Order::create([
            'table_number' => '5',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 28000, 'qty' => 2, 'spicy_level' => 3],
            ],
            'total_amount' => 56000,
            'status' => 'pending',
        ]);

        $this->actingAs($kasir)
            ->get('/kasir/order/'.$order->id.'/struk')
            ->assertOk()
            ->assertSee('SAMBAL BAKAR')
            ->assertSee('Ayam Bakar');
    }
}
