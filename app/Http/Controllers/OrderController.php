<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function cashier()
    {
        $products = Product::orderBy('category')->orderBy('name')->get();

        return view('cashier.index', compact('products'));
    }

    public function payments(Request $request)
    {
        $search = strtoupper(trim((string) $request->query('cari', '')));
        $foundOrder = null;

        if ($search !== '') {
            $foundOrder = Order::query()
                ->where('tracking_code', $search)
                ->first()
                ?? (ctype_digit($search) ? Order::find((int) $search) : null);

            $recentOrders = $foundOrder ? collect([$foundOrder]) : collect();
        } else {
            $recentOrders = Order::query()
                ->latest()
                ->take(20)
                ->get();
        }

        $pendingPayments = Order::query()
            ->where('payment_status', Order::STATUS_PENDING)
            ->latest()
            ->get();

        return view('cashier.payments', compact('recentOrders', 'pendingPayments', 'search'));
    }

    public function notifications()
    {
        $pendingPayments = Order::query()
            ->where('payment_status', Order::STATUS_PENDING)
            ->count();

        $latestOrderId = Order::query()->max('id') ?? 0;

        return response()->json([
            'pending_payments' => $pendingPayments,
            'latest_order_id' => (int) $latestOrderId,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'total_amount' => 'required|integer|min:0',
            'payment_method' => 'nullable|in:cash,transfer,qris',
        ]);

        $method = $data['payment_method'] ?? Order::PAYMENT_CASH;
        $autoPaid = in_array($method, [Order::PAYMENT_TRANSFER, Order::PAYMENT_QRIS], true);

        $order = DB::transaction(function () use ($data, $method, $autoPaid) {
            $order = Order::create([
                'table_number' => $data['table_number'],
                'items' => $data['items'],
                'total_amount' => $data['total_amount'],
                'status' => Order::ORDER_PENDING,
                'payment_method' => $method,
                'payment_status' => $autoPaid ? Order::STATUS_PAID : Order::STATUS_PENDING,
                'paid_at' => $autoPaid ? now() : null,
                'confirmed_at' => $autoPaid ? now() : null,
            ]);

            if ($autoPaid) {
                $this->recordIncome($order);
            }

            return $order;
        });

        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'paid' => $order->isPaid(),
            'status_label' => $order->statusLabel(),
        ]);
    }

    public function confirmPayment(Order $order)
    {
        if ($order->isPaid()) {
            return redirect()
                ->route('kasir.payments')
                ->with('status', 'Pembayaran order #'.$order->id.' sudah dikonfirmasi sebelumnya.');
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'payment_status' => Order::STATUS_PAID,
                'paid_at' => now(),
                'confirmed_at' => $order->confirmed_at ?? now(),
            ]);

            $this->recordIncome($order);
        });

        return redirect()
            ->route('kasir.payments')
            ->with('status', 'Pembayaran '.$order->paymentMethodLabel().' order #'.$order->id.' berhasil dikonfirmasi.');
    }

    public function printReceipt(Order $order)
    {
        return view('orders.receipt', compact('order'));
    }

    // Tampilkan halaman dashboard dapur
    public function kitchen()
    {
        return view('kitchen.index');
    }

    // API untuk mengambil pesanan yang sedang diproses (AJAX)
    public function getActiveOrders()
    {
        $orders = Order::whereNotNull('confirmed_at')
            ->whereIn('status', [Order::ORDER_PENDING, Order::ORDER_COOKING])
            ->orderBy('created_at', 'asc') // Pesanan paling lama di atas
            ->get();

        return response()->json($orders);
    }

    // API untuk mengubah status pesanan (AJAX)
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,cooking,served',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $data['status'];
        $order->save();

        return response()->json(['status' => 'success']);
    }

    private function recordIncome(Order $order): void
    {
        $exists = Transaction::query()
            ->where('order_id', $order->id)
            ->where('type', Transaction::TYPE_INCOME)
            ->exists();

        if ($exists) {
            return;
        }

        Transaction::create([
            'type' => Transaction::TYPE_INCOME,
            'amount' => $order->total_amount,
            'category' => 'Penjualan',
            'description' => 'Pesanan #'.$order->id.' - Meja '.$order->table_number,
            'transaction_date' => now()->toDateString(),
            'order_id' => $order->id,
        ]);
    }
}
