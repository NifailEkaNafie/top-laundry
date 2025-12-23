// --- PENGAMAN GANDA: Mencegah script jalan 2x ---
if (!window.dashboardScriptLoaded) {
    window.dashboardScriptLoaded = true;

    // Ambil token
    var token = localStorage.getItem('token');

    // Konfigurasi Axios Global
    // PENTING: Tambahkan Header Accept agar server membalas dengan JSON
    axios.defaults.headers.common['Accept'] = 'application/json';

    if (token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    } else {
        console.warn('Token tidak ditemukan! Anda mungkin perlu login ulang.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 Dashboard JS Siap!");
        loadOrders();
        loadCustomers();
        setupPriceCalculator();
    });

    // --- FUNGSI GLOBAL ---

    window.loadCustomers = async function() {
        console.log("🔄 Mengambil data customer...");
        try {
            const response = await axios.get('/api/customers');
            console.log("✅ Response Server:", response); // Cek ini di Console!

            const select = document.getElementById('customer_id');
            if(!select) return;

            // Logika Pembacaan Data yang Fleksibel
            let customers = [];

            // Cek apakah response berupa HTML (Tanda error auth)
            if (typeof response.data === 'string' && response.data.includes('<html')) {
                console.error("❌ Error: Server membalas dengan halaman HTML (Mungkin token expired). Login ulang!");
                // Opsional: window.location.href = '/login';
                return;
            }

            if (Array.isArray(response.data)) {
                customers = response.data;
            } else if (response.data.data && Array.isArray(response.data.data)) {
                customers = response.data.data;
            }

            // Reset dropdown
            select.innerHTML = '<option value="">Pilih Customer</option>';

            if (customers.length === 0) {
                console.warn("⚠️ Data customer kosong/tidak terbaca.");
                // Tambahkan opsi debug
                const option = document.createElement('option');
                option.text = "(Database Kosong / Error)";
                select.appendChild(option);
            } else {
                customers.forEach(customer => {
                    const option = document.createElement('option');
                    option.value = customer.id;
                    option.textContent = `${customer.name} - ${customer.phone}`;
                    select.appendChild(option);
                });
                console.log(`✅ Berhasil memuat ${customers.length} customer.`);
            }

        } catch (error) {
            console.error('❌ Gagal load customers:', error);
            if (error.response && error.response.status === 401) {
                console.warn("🔒 Token Expired. Redirecting...");
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
        }
    };

    window.loadOrders = async function() {
        try {
            const response = await axios.get('/api/orders');
            const orders = Array.isArray(response.data) ? response.data : (response.data.data ? response.data.data : []);
            const tbody = document.getElementById('orders-table-body');

            if(tbody) {
                tbody.innerHTML = '';
                if (orders.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada data order.</td></tr>';
                } else {
                    orders.forEach(order => {
                        const tr = document.createElement('tr');
                        tr.className = "hover:bg-gray-50";
                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${order.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${order.customer ? order.customer.name : 'Umum'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">${(order.service_type || '').replace(/-/g, ' ')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">${order.weight_kg} Kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp ${new Intl.NumberFormat('id-ID').format(order.price_total)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${order.status === 'Selesai' ? 'bg-green-100 text-green-800' :
                                    (order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')}">
                                    ${order.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <button onclick="deleteOrder(${order.id})" class="text-red-600 hover:text-red-900 ml-2 font-bold">Hapus</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            }
        } catch (error) {
            console.error('Error loading orders:', error);
        }
    };

    window.setupPriceCalculator = function() {
        const serviceSelect = document.getElementById('service_type');
        const weightInput = document.getElementById('weight_kg');
        const priceInput = document.getElementById('price_total');
        if(!serviceSelect || !weightInput || !priceInput) return;

        const prices = { 'cuci-kering': 5000, 'cuci-setrika': 8000, 'cuci-basah': 4000, 'setrika-saja': 4000, 'dry-clean': 15000 };
        function calculate() {
            const service = serviceSelect.value;
            const weight = parseFloat(weightInput.value) || 0;
            if (service && prices[service] && weight > 0) priceInput.value = prices[service] * weight;
            else priceInput.value = '';
        }
        serviceSelect.addEventListener('change', calculate);
        weightInput.addEventListener('input', calculate);
    };

    window.deleteOrder = async function(id) {
        if (!confirm('Hapus order ini?')) return;
        try {
            await axios.delete(`/api/orders/${id}`);
            loadOrders();
            Swal.fire('Terhapus!', '', 'success');
        } catch (error) {
            Swal.fire('Error', 'Gagal menghapus.', 'error');
        }
    };

    // Form Submit Listener (Update/Create)
    const orderForm = document.getElementById('orderForm');
    if(orderForm) {
        const newOrderForm = orderForm.cloneNode(true);
        orderForm.parentNode.replaceChild(newOrderForm, orderForm);

        newOrderForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            try {
                await axios.post('/api/orders', data);
                document.getElementById('orderModal').classList.add('hidden');
                this.reset();
                Swal.fire('Berhasil', 'Order disimpan', 'success');
                loadOrders();
            } catch (error) {
                console.error(error);
                let msg = 'Gagal menyimpan data';
                if(error.response && error.response.data && error.response.data.message) msg = error.response.data.message;
                Swal.fire('Gagal', msg, 'error');
            }
        });
    }
}
