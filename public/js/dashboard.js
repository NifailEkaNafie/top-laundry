// --- PENGAMAN GANDA: Mencegah script jalan 2x ---
if (!window.dashboardScriptLoaded) {
    window.dashboardScriptLoaded = true;

    // Gunakan 'var' (bukan const) agar tidak error jika file ter-load 2x
    var token = localStorage.getItem('token');

    // Konfigurasi Axios Global
    if (token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    } else {
        // Jangan redirect paksa di sini untuk menghindari loop, cukup log saja
        console.warn('Token tidak ditemukan, fitur mungkin terbatas.');
    }

    // Jalankan fungsi saat halaman siap
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Dashboard JS Loaded & Ready!");
        loadOrders();
        loadCustomers();
        setupPriceCalculator();
    });

    // --- FUNGSI GLOBAL (window.) agar bisa dipanggil HTML ---

    window.loadCustomers = async function() {
        console.log("Memulai loadCustomers..."); // Debug log
        try {
            const response = await axios.get('/api/customers');
            const select = document.getElementById('customer_id');

            if(!select) {
                console.error("Elemen dropdown 'customer_id' tidak ditemukan!");
                return;
            }

            // Normalisasi Data (Jaga-jaga format {data: []} atau [])
            let customers = [];
            if (Array.isArray(response.data)) {
                customers = response.data;
            } else if (response.data.data && Array.isArray(response.data.data)) {
                customers = response.data.data;
            } else if (response.data.success && response.data.data) { // Handle format {success:true, data:[]}
                customers = response.data.data;
            }

            // Reset dropdown
            select.innerHTML = '<option value="">Pilih Customer</option>';

            if (customers.length === 0) {
                console.warn("Database customer kosong!");
            }

            customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.id;
                option.textContent = `${customer.name} - ${customer.phone}`;
                select.appendChild(option);
            });
            console.log("Dropdown customer berhasil diisi:", customers.length, "data.");

        } catch (error) {
            console.error('Gagal load customers:', error);
            if (error.response && error.response.status === 401) {
                console.error("Sesi habis, redirecting...");
                window.location.href = '/login';
            }
        }
    };

    window.loadOrders = async function() {
        try {
            const response = await axios.get('/api/orders');
            // Normalisasi data order
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
                                <button onclick="deleteOrder(${order.id})" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
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

    // Form Submit Listener (Hanya pasang jika form ada)
    const orderForm = document.getElementById('orderForm');
    if(orderForm) {
        // Hapus listener lama (kloning elemen) agar tidak double submit
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
                Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
            }
        });
    }
}
