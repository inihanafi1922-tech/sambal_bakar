@extends('layouts.staff')

@section('title', 'Dapur - Sambal Bakar')
@section('subtitle', 'Dashboard Dapur')

@section('content')
    <style>
        .board {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
        }

        .order-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 16px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .oc-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
        .oc-head h3 { margin: 0; font-size: 16px; font-weight: 800; }
        .oc-head p { margin: 3px 0 0; font-size: 12px; color: var(--muted); }

        .oc-items { display: flex; flex-direction: column; gap: 8px; }
        .oc-item { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; font-size: 13px; border-bottom: 1px dashed #f0e6db; padding-bottom: 8px; }
        .oc-item:last-child { border-bottom: 0; padding-bottom: 0; }
        .oc-item strong { font-weight: 700; }
        .oc-item span { color: var(--muted); font-size: 12px; }
        .oc-note { color: #9a5b00; font-size: 11px; font-weight: 700; }

        .oc-actions { display: flex; gap: 8px; margin-top: auto; }
        .oc-actions button {
            flex: 1;
            border: 0;
            border-radius: 10px;
            padding: 10px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            background: var(--brand);
        }
        .oc-actions button.cook { background: #9a5b00; }
        .oc-actions button.done { background: #1e7a3d; }
        .oc-actions button:hover { opacity: .9; }

        .board-empty {
            grid-column: 1 / -1;
            padding: 50px 20px;
            text-align: center;
            color: var(--muted);
            background: var(--surface);
            border: 1px dashed var(--line);
            border-radius: 16px;
        }

        .refresh-note { font-size: 12px; color: var(--muted); }
    </style>

    <div class="page-head" style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <div>
            <h2>Pesanan Aktif</h2>
            <p>Pesanan yang perlu dimasak dan disajikan. Diperbarui otomatis setiap 5 detik.</p>
        </div>
        <button type="button" class="btn" id="refreshBtn" style="background:#231a15;">Muat Ulang</button>
    </div>

    <div class="board" id="board">
        <div class="board-empty">Memuat pesanan...</div>
    </div>

    <p class="refresh-note" id="refreshNote" style="margin-top:14px;"></p>

    <script>
        const ordersUrl = '{{ route('orders.active') }}';
        const statusBase = '{{ url('/api/orders') }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const board = document.getElementById('board');
        const refreshNote = document.getElementById('refreshNote');
        const refreshBtn = document.getElementById('refreshBtn');

        const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[char]));

        function render(orders) {
            if (!orders.length) {
                board.innerHTML = '<div class="board-empty">Belum ada pesanan aktif. Semua pesanan sudah selesai.</div>';
                return;
            }

            board.innerHTML = orders.map((order) => {
                const items = (order.items || []).map((item) => `
                    <div class="oc-item">
                        <div>
                            <strong>${escapeHtml(item.name || '-')}</strong>
                            ${item.spicy_level ? `<div class="oc-note">Level pedas: ${item.spicy_level}</div>` : ''}
                        </div>
                        <span>x${item.qty || 0}</span>
                    </div>
                `).join('');

                const action = order.status === 'pending'
                    ? `<button type="button" class="cook" data-id="${order.id}" data-status="cooking">Mulai Masak</button>`
                    : `<button type="button" class="done" data-id="${order.id}" data-status="served">Selesai &amp; Sajikan</button>`;

                return `
                    <article class="order-card">
                        <div class="oc-head">
                            <div>
                                <h3>Meja ${escapeHtml(order.table_number)}</h3>
                                <p>Order #${order.id}</p>
                            </div>
                            <span class="badge ${order.status}">${order.status}</span>
                        </div>
                        <div class="oc-items">${items}</div>
                        <div class="oc-actions">${action}</div>
                    </article>
                `;
            }).join('');
        }

        async function loadOrders() {
            try {
                const response = await fetch(ordersUrl, { headers: { 'Accept': 'application/json' } });
                const orders = await response.json();
                render(orders);
                refreshNote.textContent = 'Terakhir diperbarui: ' + new Date().toLocaleTimeString('id-ID');
            } catch (error) {
                refreshNote.textContent = 'Gagal memuat pesanan. Periksa koneksi.';
            }
        }

        async function updateStatus(id, status) {
            try {
                const response = await fetch(statusBase + '/' + id + '/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: status })
                });

                if (!response.ok) throw new Error('Gagal mengubah status.');

                await loadOrders();
            } catch (error) {
                refreshNote.textContent = error.message;
            }
        }

        board.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-status]');
            if (!button) return;
            updateStatus(button.dataset.id, button.dataset.status);
        });

        refreshBtn.addEventListener('click', loadOrders);

        loadOrders();
        setInterval(loadOrders, 5000);
    </script>
@endsection
