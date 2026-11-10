<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_order_returns_tracking_code_and_track_page_works(): void
    {
        $product = Product::create([
            'name' => 'Ayam Bakar',
            'price' => 28000,
            'category' => 'Makanan',
            'has_spicy_level' => true,
        ]);

        $response = $this->postJson('/pesan', [
            'table_number' => 'Budi',
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'qty' => 2, 'spicy_level' => 3],
            ],
        ])->assertOk();

        $code = $response->json('tracking_code');

        $this->assertMatchesRegularExpression('/^SB[A-Z2-9]{6}$/', $code);
        $this->assertStringContainsString('/lacak/'.$code, $response->json('track_url'));

        $this->get('/lacak/'.$code)
            ->assertOk()
            ->assertSee($code)
            ->assertSee('Budi')
            ->assertSee('Ayam Bakar');

        $this->getJson(route('pelanggan.track.status', $code))
            ->assertOk()
            ->assertJsonFragment(['tracking_code' => $code, 'paid' => false]);
    }

    public function test_track_page_shows_not_found_for_unknown_code(): void
    {
        $this->get('/lacak/SBZZZZ99')
            ->assertOk()
            ->assertSee('tidak ditemukan');
    }

    public function test_kasir_can_find_order_by_tracking_code(): void
    {
        $kasir = User::factory()->create(['role' => User::ROLE_KASIR]);

        $product = Product::create([
            'name' => 'Ayam Bakar',
            'price' => 28000,
            'category' => 'Makanan',
        ]);

        $this->postJson('/pesan', [
            'table_number' => 'Meja 3',
            'items' => [
                ['product_id' => $product->id, 'qty' => 1],
            ],
        ])->assertOk();

        $code = Order::latest('id')->firstOrFail()->tracking_code;

        $this->actingAs($kasir)
            ->get('/kasir/pembayaran?cari='.$code)
            ->assertOk()
            ->assertSee($code)
            ->assertSee('Meja 3');
    }
}
