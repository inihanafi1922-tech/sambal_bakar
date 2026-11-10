<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Dashboard - Real-time</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">

    <header class="bg-gray-800 p-4 shadow-lg border-b border-gray-700 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-yellow-400">🔥 Kitchen Display System</h1>
        <div class="flex items-center gap-3 text-sm">
            <span class="flex h-3 w-3 relative">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            System Live
        </div>
    </header>

    <!-- Container Pesanan -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="order-container">
        <!-- Pesanan akan di-render di sini oleh JavaScript -->
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fungsi mengambil data pesanan dari server
        async function fetchOrders() {
            try {
                const response = await fetch('/api/orders/active');
                const orders = await response.json();
                renderOrders(orders);
            } catch (error) {
                console.error('Gagal mengambil data:', error);
            }
        }

        // Fungsi menggambar ulang UI pesanan
        function renderOrders(orders) {
            const container = document.getElementById('order-container');
            container.innerHTML = '';

            if (orders.length === 0) {
                container.innerHTML = `<div class="col-span-full text-center text-gray-500 mt-10 text-xl">Tidak ada pesanan aktif. Dapur bersih! ✨</div>`;
                return;
            }

            orders.forEach(order => {
                // Tentukan warna border berdasarkan status
                const isCooking = order.status === 'cooking';
                const borderColor = isCooking ? 'border-yellow-500' : 'border-red-500';
                const headerColor = isCooking ? 'bg-yellow-600' : 'bg-red-600';
                const statusText = isCooking ? 'Sedang Dimasak' : 'Pesanan Baru';

                // Parsing items (karena disimpan dalam JSON)
                let itemsList = '';
                let parsedItems = typeof order.items === 'string' ? JSON.parse(order.items) : order.items;
                
                parsedItems.forEach(item => {
                    let levelBadge = item.level !== "0" ? `<span class="bg-red-500 text-xs px-2 py-1 rounded ml-2 text-white">Lvl ${item.level}</span>` : '';
                    itemsList += `
                        <li class="py-2 border-b border-gray-700 flex justify-between items-start">
                            <div>
                                <span class="font-bold text-lg">${item.qty}x</span> ${item.name}
                                ${levelBadge}
                            </div>
                        </li>
                    `;
                });

                // Render Kartu Pesanan
                const card = `
                    <div class="bg-gray-800 rounded-lg border-2 ${borderColor} overflow-hidden shadow-xl flex flex-col justify-between">
                        <div class="${headerColor} p-3 flex justify-between items-center text-white">
                            <span class="text-xl font-bold">Meja ${order.table_number}</span>
                            <span class="text-sm font-semibold px-2 py-1 bg-black bg-opacity-30 rounded">${statusText}</span>
                        </div>
                        <div class="p-4 flex-grow">
                            <ul class="text-gray-300">
                                ${itemsList}
                            </ul>
                        </div>
                        <div class="p-4 bg-gray-900 border-t border-gray-700">
                            ${isCooking 
                                ? `<button onclick="updateStatus(${order.id}, 'served')" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded text-lg transition">✅ Selesai (Sajikan)</button>`
                                : `<button onclick="updateStatus(${order.id}, 'cooking')" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-3 rounded text-lg transition">🍳 Mulai Masak</button>`
                            }
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }

        // Fungsi mengubah status pesanan (Baru -> Dimasak -> Selesai)
        async function updateStatus(orderId, newStatus) {
            try {
                await fetch(`/api/orders/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                fetchOrders(); // Refresh UI langsung setelah update
            } catch (error) {
                console.error('Gagal update status:', error);
            }
        }

        // Tarik data pertama kali halaman dimuat
        fetchOrders();

        // Refresh data setiap 5 detik (5000 milidetik) secara otomatis
        setInterval(fetchOrders, 5000);
    </script>
</body>
</html>