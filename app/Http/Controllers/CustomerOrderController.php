<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CustomerOrderController extends Controller
{
    public function menu(Request $request)
    {
        $products = Product::query()
            ->where(function ($query) {
                $query->whereNull('stock')->orWhere('stock', '>', 0);
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $tableHint = $request->query('meja');
        $tableHint = is_string($tableHint) ? mb_substr(trim($tableHint), 0, 20) : '';

        return view('customer.menu', [
            'products' => $products,
            'tableHint' => $tableHint,
            'paymentSetting' => PaymentSetting::first(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => ['required', 'string', 'max:20'],
            'payment_method' => ['nullable', 'in:cash,transfer,qris'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:50'],
            'items.*.spicy_level' => ['nullable', 'integer', 'between:1,5'],
        ]);

        $method = $data['payment_method'] ?? Order::PAYMENT_CASH;

        $incomingItems = [];

        foreach ($data['items'] as $incoming) {
            $productId = (int) $incoming['product_id'];
            $qty = (int) $incoming['qty'];

            if (isset($incomingItems[$productId])) {
                $incomingItems[$productId]['qty'] += $qty;
            } else {
                $incomingItems[$productId] = [
                    'qty' => $qty,
                    'spicy_level' => isset($incoming['spicy_level']) ? (int) $incoming['spicy_level'] : null,
                ];
            }
        }

        $products = Product::query()
            ->whereIn('id', array_keys($incomingItems))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($incomingItems as $productId => $incoming) {
            $product = $products->get($productId);
            $qty = $incoming['qty'];

            if ($product->stock !== null && $qty > $product->stock) {
                return response()->json([
                    'message' => 'Menu '.$product->name.' stoknya tidak cukup (tersisa '.$product->stock.').',
                ], 422);
            }

            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $qty,
                'spicy_level' => $product->has_spicy_level
                    ? ($incoming['spicy_level'] ?? 1)
                    : null,
            ];

            $total += $product->price * $qty;
        }

        $order = Order::create([
            'table_number' => $data['table_number'],
            'items' => $items,
            'total_amount' => $total,
            'status' => Order::ORDER_PENDING,
            'payment_method' => $method,
            'payment_status' => Order::STATUS_PENDING,
            'paid_at' => null,
            'confirmed_at' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
            'tracking_code' => $order->tracking_code,
            'track_url' => route('pelanggan.track', ['code' => $order->tracking_code]),
            'total_amount' => $order->total_amount,
            'payment_method' => $order->payment_method,
            'status_label' => $order->statusLabel(),
            'status_url' => URL::signedRoute('pelanggan.order.status', ['order' => $order->id]),
        ]);
    }

    public function status(Order $order)
    {
        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'status_label' => $order->statusLabel(),
            'payment_status' => $order->payment_status,
            'paid' => $order->isPaid(),
        ]);
    }

    public function trackForm(Request $request)
    {
        if ($request->filled('code')) {
            $code = strtoupper(trim((string) $request->input('code')));

            return redirect()->route('pelanggan.track', ['code' => $code]);
        }

        return view('customer.track', ['order' => null]);
    }

    public function track(string $code)
    {
        $order = $this->findOrderByCode($code);

        return view('customer.track', [
            'order' => $order,
            'code' => strtoupper(trim($code)),
        ]);
    }

    public function trackStatus(string $code)
    {
        $order = $this->findOrderByCode($code);

        if (! $order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json([
            'order_id' => $order->id,
            'tracking_code' => $order->tracking_code,
            'status' => $order->status,
            'status_label' => $order->statusLabel(),
            'payment_status' => $order->payment_status,
            'paid' => $order->isPaid(),
        ]);
    }

    private function findOrderByCode(string $code): ?Order
    {
        $code = strtoupper(trim($code));

        return Order::query()
            ->where('tracking_code', $code)
            ->first()
            ?? (ctype_digit($code) ? Order::find((int) $code) : null);
    }
}
