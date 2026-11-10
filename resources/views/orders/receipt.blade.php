<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Order #{{ $order->id }} - Sambal Bakar</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 16px;
            background: #f1ece6;
            font-family: 'Courier New', ui-monospace, monospace;
            color: #1a1a1a;
        }

        .receipt {
            width: 300px;
            margin: 0 auto;
            background: #fff;
            padding: 18px 16px;
            box-shadow: 0 12px 30px -18px rgba(0, 0, 0, .4);
        }

        .center { text-align: center; }
        .brand { font-size: 18px; font-weight: 700; letter-spacing: 1px; }
        .brand-sub { font-size: 11px; margin-top: 3px; }

        .divider { border-top: 1px dashed #999; margin: 12px 0; }

        .meta { font-size: 11px; line-height: 1.7; }
        .meta-row { display: flex; justify-content: space-between; gap: 10px; }

        .items { width: 100%; border-collapse: collapse; font-size: 11px; }
        .items th { text-align: left; padding-bottom: 6px; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #666; }
        .items td { padding: 3px 0; vertical-align: top; }
        .items .right { text-align: right; }
        .item-name { font-weight: 700; }
        .item-note { color: #666; font-size: 10px; }

        .total-row { display: flex; justify-content: space-between; font-size: 14px; font-weight: 700; }

        .footer { margin-top: 16px; font-size: 10px; text-align: center; line-height: 1.6; color: #555; }

        .status {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 8px;
            border: 1px solid #999;
            font-size: 10px;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .actions { width: 300px; margin: 14px auto 0; display: flex; gap: 8px; }
        .actions button {
            flex: 1;
            padding: 10px;
            border: 0;
            border-radius: 8px;
            background: #d62828;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }
        .actions button.secondary { background: #fff; color: #1a1a1a; border: 1px solid #ccc; }

        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; width: 100%; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <div class="brand">SAMBAL BAKAR</div>
            <div class="brand-sub">Pesan langsung dari meja</div>
        </div>

        <div class="divider"></div>

        <div class="meta">
            <div class="meta-row"><span>No. Order</span><span>#{{ $order->id }}</span></div>
            <div class="meta-row"><span>Waktu</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="meta-row"><span>Meja / Pelanggan</span><span>{{ $order->table_number }}</span></div>
            <div class="meta-row"><span>Pembayaran</span><span>{{ $order->paymentMethodLabel() }}</span></div>
            <div class="meta-row"><span>Status Bayar</span><span>{{ $order->isPaid() ? 'LUNAS' : 'MENUNGGU KONFIRMASI' }}</span></div>
        </div>

        <div class="divider"></div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Qty</th>
                    <th class="right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item['name'] ?? '-' }}</div>
                            @if (! empty($item['spicy_level']))
                                <div class="item-note">Level pedas: {{ $item['spicy_level'] }}</div>
                            @endif
                        </td>
                        <td class="right">{{ $item['qty'] ?? 0 }}</td>
                        <td class="right">Rp {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total-row">
            <span>TOTAL</span>
            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>

        <div class="center">
            <span class="status">{{ $order->statusLabel() }}</span>
            <span class="status">{{ $order->isPaid() ? 'lunas' : 'belum lunas' }}</span>
        </div>

        <div class="divider"></div>

        <div class="footer">
            Terima kasih atas pesanan Anda.<br>
            Struk ini dicetak otomatis oleh sistem.
        </div>
    </div>

    <div class="actions">
        <button type="button" onclick="window.print()">Cetak Struk</button>
        <button type="button" class="secondary" onclick="window.close()">Tutup</button>
    </div>

    @if (request('auto'))
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</body>
</html>
