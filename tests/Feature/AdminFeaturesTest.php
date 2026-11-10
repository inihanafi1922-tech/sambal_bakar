<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_menu_and_stock(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin/menu')->assertOk()->assertSee('Pengaturan Menu');

        $this->actingAs($admin)->post('/admin/menu', [
            'name' => 'Menu Uji',
            'price' => 15000,
            'category' => 'Makanan',
            'stock' => 12,
            'has_spicy_level' => 1,
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Menu Uji')->firstOrFail();

        $this->assertSame(12, $product->stock);

        $this->actingAs($admin)->put('/admin/menu/'.$product->id, [
            'name' => 'Menu Uji Diubah',
            'price' => 18000,
            'category' => 'Makanan',
            'stock' => '',
            'has_spicy_level' => 1,
        ])->assertRedirect(route('admin.products.index'));

        $product->refresh();

        $this->assertSame('Menu Uji Diubah', $product->name);
        $this->assertSame(18000, $product->price);
        $this->assertNull($product->stock);
    }

    public function test_admin_can_only_view_staff_on_duty_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $schedule = StaffSchedule::create([
            'user_id' => $admin->id,
            'duty_date' => today()->toDateString(),
            'shift' => 'Pagi',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Staf Bertugas')
            ->assertSee($admin->name)
            ->assertDontSee('Tambah Jadwal Staf')
            ->assertDontSee('Hapus');

        $this->actingAs($admin)->post('/admin/staf', [
            'user_id' => $admin->id,
            'duty_date' => today()->toDateString(),
            'shift' => 'Siang',
        ])->assertNotFound();

        $this->actingAs($admin)
            ->delete('/admin/staf/'.$schedule->id)
            ->assertNotFound();

        $this->assertDatabaseHas('staff_schedules', ['id' => $schedule->id]);
    }

    public function test_admin_can_record_income_and_expense(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->post('/admin/transaksi', [
            'type' => 'expense',
            'amount' => 25000,
            'category' => 'Bahan Baku',
            'description' => 'Belanja ayam',
            'transaction_date' => today()->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('transactions', [
            'type' => 'expense',
            'amount' => 25000,
        ]);

        $this->actingAs($admin)->get('/admin/laporan')->assertOk()->assertSee('Laporan Keuangan');
    }

    public function test_new_order_is_recorded_as_income(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->postJson('/kasir/order', [
            'table_number' => '7',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 28000, 'qty' => 1, 'spicy_level' => 2],
            ],
            'total_amount' => 28000,
            'payment_method' => 'qris',
        ])->assertOk();

        $this->assertDatabaseHas('transactions', [
            'type' => 'income',
            'amount' => 28000,
        ]);
    }

    public function test_cash_payment_needs_confirmation_before_income_is_recorded(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->postJson('/kasir/order', [
            'table_number' => '9',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 30000, 'qty' => 1, 'spicy_level' => 2],
            ],
            'total_amount' => 30000,
            'payment_method' => 'cash',
        ])->assertOk();

        $order = Order::latest('id')->firstOrFail();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(Order::ORDER_PENDING, $order->status);
        $this->assertFalse($order->isConfirmed());
        $this->assertNull($order->confirmed_at);
        $this->assertDatabaseMissing('transactions', ['order_id' => $order->id]);

        $this->actingAs($kasir)->get(route('kasir.payments'))->assertOk()->assertSee('Pesanan & Konfirmasi Pembayaran');

        $this->actingAs($admin)->getJson(route('orders.active'))->assertOk()->assertJsonCount(0);

        $this->actingAs($kasir)
            ->post(route('kasir.payment.confirm', $order))
            ->assertRedirect(route('kasir.payments'));

        $order->refresh();

        $this->assertSame('paid', $order->payment_status);
        $this->assertSame(Order::ORDER_PENDING, $order->status);
        $this->assertTrue($order->isConfirmed());
        $this->assertDatabaseHas('transactions', [
            'order_id' => $order->id,
            'type' => 'income',
            'amount' => 30000,
        ]);

        $this->actingAs($admin)
            ->getJson(route('orders.active'))
            ->assertOk()
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_transfer_payment_is_automatically_confirmed(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)->postJson('/kasir/order', [
            'table_number' => '10',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 25000, 'qty' => 1, 'spicy_level' => 2],
            ],
            'total_amount' => 25000,
            'payment_method' => 'transfer',
        ])->assertOk()->assertJson(['paid' => true]);

        $order = Order::latest('id')->firstOrFail();

        $this->assertSame('paid', $order->payment_status);
        $this->assertSame(Order::ORDER_PENDING, $order->status);
        $this->assertTrue($order->isConfirmed());
        $this->assertNotNull($order->paid_at);
        $this->assertDatabaseHas('transactions', [
            'order_id' => $order->id,
            'type' => 'income',
            'amount' => 25000,
        ]);

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->getJson(route('orders.active'))
            ->assertOk()
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_kasir_can_fill_cashier_guard_name_and_admin_can_see_it(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($kasir)->get('/kasir/staf')->assertOk()->assertSee('Isi Nama Penjaga Kasir');

        $this->actingAs($kasir)->post('/kasir/staf', [
            'name' => 'Penjaga Uji',
            'duty_date' => today()->toDateString(),
            'shift' => 'Pagi',
        ])->assertRedirect(route('kasir.staff'));

        $this->assertDatabaseHas('staff_schedules', [
            'name' => 'Penjaga Uji',
        ]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Penjaga Uji');
    }

    public function test_kasir_cannot_delete_todays_schedule(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $schedule = StaffSchedule::create([
            'user_id' => $kasir->id,
            'name' => 'Penjaga Hari Ini',
            'duty_date' => today()->toDateString(),
            'shift' => 'Pagi',
        ]);

        $this->actingAs($kasir)
            ->delete(route('kasir.staff.destroy', $schedule))
            ->assertSessionHasErrors('duty_date');

        $this->assertDatabaseHas('staff_schedules', ['id' => $schedule->id]);
    }

    public function test_kasir_can_delete_upcoming_schedule(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $schedule = StaffSchedule::create([
            'user_id' => $kasir->id,
            'name' => 'Penjaga Besok',
            'duty_date' => today()->addDay()->toDateString(),
            'shift' => 'Pagi',
        ]);

        $this->actingAs($kasir)
            ->delete(route('kasir.staff.destroy', $schedule))
            ->assertRedirect(route('kasir.staff'));

        $this->assertDatabaseMissing('staff_schedules', ['id' => $schedule->id]);
    }

    public function test_cashier_can_receive_payment_notifications(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $order = Order::create([
            'table_number' => '11',
            'items' => [
                ['product_id' => 1, 'name' => 'Ayam Bakar', 'price' => 28000, 'qty' => 1, 'spicy_level' => 2],
            ],
            'total_amount' => 28000,
            'status' => Order::ORDER_PENDING,
            'payment_method' => Order::PAYMENT_CASH,
            'payment_status' => Order::STATUS_PENDING,
        ]);

        $this->actingAs($kasir)
            ->getJson(route('kasir.notifications'))
            ->assertOk()
            ->assertJson([
                'pending_payments' => 1,
                'latest_order_id' => $order->id,
            ]);
    }

    public function test_cashier_page_only_shows_staff_and_cashier_feature_links(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $this->actingAs($kasir)
            ->get('/kasir')
            ->assertOk()
            ->assertSee('Staf')
            ->assertDontSee('Pengaturan Menu')
            ->assertDontSee('Laporan');
    }
}
