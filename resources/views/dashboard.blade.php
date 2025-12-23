<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Top Laundry</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white shadow p-4 flex justify-between items-center mb-6">
        <div class="flex items-center gap-2">
            <a href="/dashboard" class="text-xl font-bold text-blue-600">🧺 Top Laundry</a>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-600 text-sm hidden sm:inline">Halo, Admin</span>
            <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition flex items-center gap-2">
                <span>Keluar</span> 🚪
            </button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pesanan Laundry</h2>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
                <span>+</span> Tambah Order
            </button>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Berat (Kg)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total (Rp)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body" class="bg-white divide-y divide-gray-200">
                        <tr><td colspan="7" class="text-center py-4 text-gray-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Form Order Baru</h3>

                        <form id="orderForm">
                            <input type="hidden" name="_method" value="POST">

                            <div class="mb-4">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                                <select id="customer_id" name="customer_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">Memuat data...</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="service_type" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                                <select id="service_type" name="service_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">Pilih Layanan</option>
                                    <option value="cuci-kering">Cuci Kering (Rp 5.000)</option>
                                    <option value="cuci-setrika">Cuci Setrika (Rp 8.000)</option>
                                    <option value="cuci-basah">Cuci Basah (Rp 4.000)</option>
                                    <option value="setrika-saja">Setrika Saja (Rp 4.000)</option>
                                    <option value="dry-clean">Dry Clean (Rp 15.000)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="weight_kg" class="block text-sm font-medium text-gray-700">Berat (Kg)</label>
                                    <input type="number" step="0.1" id="weight_kg" name="weight_kg" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label for="price_total" class="block text-sm font-medium text-gray-700">Total (Rp)</label>
                                    <input type="number" id="price_total" name="price_total" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="pending">Pending</option>
                                    <option value="Di Proses">Di Proses</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:col-start-2 sm:text-sm">
                                    Simpan Order
                                </button>
                                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        console.log("🚀 Dashboard Script Loaded!");

        // --- KONFIGURASI AXIOS & AUTH ---
        const token = localStorage.getItem('token');

        if (!token) {
            alert("Sesi habis. Silakan login kembali.");
            window.location.href = '/login';
        } else {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.defaults.headers.common['Accept'] = 'application/json'; // PENTING: Paksa JSON
        }

        // --- FUNGSI MODAL ---
        function openModal() {
            document.getElementById('orderModal').classList.remove('hidden');
            loadCustomers(); // Refresh customer saat buka modal
        }

        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.getElementById('orderForm').reset();
        }

        // --- FUNGSI LOGOUT ---
        async function logout() {
            if(!confirm("Yakin ingin keluar?")) return;
            try { await axios.post('/api/logout'); } catch(e) {}
            localStorage.removeItem('token');
            window.location.href = '/login';
        }

        // --- FUNGSI LOAD CUSTOMERS ---
        async function loadCustomers() {
            const select = document.getElementById('customer_id');
            try {
                const response = await axios.get('/api/customers');
                console.log("Customer Data:", response.data);

                // Handle berbagai format JSON
                let data = [];
                if (Array.isArray(response.data)) data = response.data;
                else if (response.data.data) data = response.data.data;
                else if (response.data.customers) data = response.data.customers;

                select.innerHTML = '<option value="">Pilih Customer</option>';

                if (data.length === 0) {
                    const opt = document.createElement('option');
                    opt.text = "-- Data Kosong / Belum Ada Customer --";
                    opt.disabled = true;
                    select.appendChild(opt);
                } else {
                    data.forEach(cust => {
                        const opt = document.createElement('option');
                        opt.value = cust.id;
                        opt.textContent = `${cust.name} - ${cust.phone}`;
                        select.appendChild(opt);
                    });
                }
            } catch (error) {
                console.error("Gagal load customer:", error);
                if(error.response && error.response.status === 401) logout();
            }
        }

        // --- FUNGSI LOAD ORDERS ---
        async function loadOrders() {
            const tbody = document.getElementById('orders-table-body');
            try {
                const response = await axios.get('/api/orders');
                let data = Array.isArray(response.data) ? response.data : (response.data.data || []);

                tbody.innerHTML = '';
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada order.</td></tr>';
                    return;
                }

                data.forEach(order => {
                    const custName = order.customer ? order.customer.name : 'Umum/Terhapus';
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-gray-50 border-b";
                    tr.innerHTML = `
                        <td class="px-6 py-4 font-medium text-gray-900">#${order.id}</td>
                        <td class="px-6 py-4 text-gray-600">${custName}</td>
                        <td class="px-6 py-4 capitalize text-gray-600">${order.service_type.replace(/-/g, ' ')}</td>
                        <td class="px-6 py-4 text-center text-gray-600">${order.weight_kg} Kg</td>
                        <td class="px-6 py-4 text-right text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(order.price_total)}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                ${order.status === 'Selesai' ? 'bg-green-100 text-green-800' :
                                (order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')}">
                                ${order.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="deleteOrder(${order.id})" class="text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

            } catch (error) {
                console.error("Gagal load orders:", error);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-red-500">Gagal memuat data.</td></tr>';
            }
        }

        // --- FUNGSI DELETE ---
        window.deleteOrder = async function(id) {
            if(!confirm("Hapus data ini?")) return;
            try {
                await axios.delete(`/api/orders/${id}`);
                loadOrders();
                Swal.fire('Terhapus', '', 'success');
            } catch(e) {
                Swal.fire('Gagal', 'Tidak bisa menghapus data', 'error');
            }
        }

        // --- KALKULATOR HARGA OTOMATIS ---
        function setupCalculator() {
            const serviceSelect = document.getElementById('service_type');
            const weightInput = document.getElementById('weight_kg');
            const priceInput = document.getElementById('price_total');

            const prices = {
                'cuci-kering': 5000,
                'cuci-setrika': 8000,
                'cuci-basah': 4000,
                'setrika-saja': 4000,
                'dry-clean': 15000
            };

            function calc() {
                const s = serviceSelect.value;
                const w = parseFloat(weightInput.value) || 0;
                if(prices[s] && w > 0) priceInput.value = prices[s] * w;
                else priceInput.value = '';
            }

            serviceSelect.addEventListener('change', calc);
            weightInput.addEventListener('input', calc);
        }

        // --- SUBMIT FORM ---
        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                await axios.post('/api/orders', data);
                closeModal();
                loadOrders();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Order tersimpan!', timer: 1500, showConfirmButton: false });
            } catch (error) {
                console.error(error);
                let msg = 'Terjadi kesalahan sistem';
                if(error.response && error.response.data && error.response.data.message) msg = error.response.data.message;
                Swal.fire('Gagal', msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });

        // --- EXECUTE START ---
        document.addEventListener("DOMContentLoaded", () => {
            loadOrders();
            setupCalculator();
        });
    </script>
</body>
</html>
