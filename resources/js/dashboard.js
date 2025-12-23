// Konfigurasi Axios agar menyertakan Token JWT di setiap request
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
} else {
    // Jika tidak ada token, tendang ke login
    window.location.href = '/login';
}

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    loadCustomers(); // <-- Ini yang sebelumnya kurang (Fungsi load customers)
    setupPriceCalculator(); // <-- Fitur hitung harga otomatis
});

// --- 1. Load Orders (Menampilkan Tabel) ---
async function loadOrders() {
    try {
        const response = await axios.get('/api/orders');
        const orders = response.data.data; // Sesuaikan dengan struktur JSON response API Anda
        const tbody = document.getElementById('orders-table-body');

        tbody.innerHTML = ''; // Bersihkan tabel

        if (orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada data order.</td></tr>';
            return;
        }

        orders.forEach(order => {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50";
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${order.id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${order.customer ? order.customer.name : 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">${order.service_type.replace(/-/g, ' ')}</td>
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
    } catch (error) {
        console.error('Error loading orders:', error);
        if (error.response && error.response.status === 401) {
            window.location.href = '/login'; // Redirect jika token expired
        }
    }
}

// --- 2. Load Customers (Mengisi Dropdown) ---
async function loadCustomers() {
    try {
        const response = await axios.get('/api/customers');
        const customers = response.data; // Asumsi response langsung array atau sesuaikan
        const select = document.getElementById('customer_id');

        // Reset opsi (sisakan opsi pertama "Pilih Customer")
        select.innerHTML = '<option value="">Pilih Customer</option>';

        // Cek apakah response berupa array langsung atau dibungkus 'data'
        const dataCustomers = Array.isArray(response.data) ? response.data : (response.data.data ? response.data.data : []);

        dataCustomers.forEach(customer => {
            const option = document.createElement('option');
            option.value = customer.id;
            option.textContent = `${customer.name} - ${customer.phone}`;
            select.appendChild(option);
        });

    } catch (error) {
        console.error('Error loading customers:', error);
    }
}

// --- 3. Kalkulator Harga Otomatis ---
function setupPriceCalculator() {
    const serviceSelect = document.getElementById('service_type');
    const weightInput = document.getElementById('weight_kg');
    const priceInput = document.getElementById('price_total');

    // Daftar Harga per Kg (Realistis)
    const prices = {
        'cuci-kering': 5000,    // Rp 5.000 / kg
        'cuci-setrika': 8000,   // Rp 8.000 / kg
        'cuci-basah': 4000,     // Rp 4.000 / kg
        'setrika-saja': 4000,   // Rp 4.000 / kg
        'dry-clean': 15000      // Rp 15.000 / kg (mahalan dikit)
    };

    function calculate() {
        const service = serviceSelect.value;
        const weight = parseFloat(weightInput.value) || 0; // Jika kosong dianggap 0

        if (service && prices[service] && weight > 0) {
            const total = prices[service] * weight;
            priceInput.value = total; // Set nilai otomatis
        } else {
            priceInput.value = ''; // Kosongkan jika data belum lengkap
        }
    }

    // Pasang "pendengar" (event listener) agar hitung otomatis saat diketik/dipilih
    serviceSelect.addEventListener('change', calculate);
    weightInput.addEventListener('input', calculate);
}

// --- 4. Submit Form Order ---
document.getElementById('orderForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Ambil data form
    const formData = new FormData(this);
    // Konversi FormData ke JSON object (kadang API lebih suka JSON raw)
    const data = Object.fromEntries(formData.entries());

    try {
        await axios.post('/api/orders', data);

        // Tutup modal & Refresh
        document.getElementById('orderModal').classList.add('hidden');
        this.reset();

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Order baru berhasil ditambahkan!',
            timer: 1500,
            showConfirmButton: false
        });

        loadOrders(); // Reload tabel

    } catch (error) {
        console.error('Submit error:', error);
        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan order.', 'error');
    }
});

// --- 5. Delete Order ---
async function deleteOrder(id) {
    if (!confirm('Yakin ingin menghapus order ini?')) return;

    try {
        await axios.delete(`/api/orders/${id}`);
        loadOrders();
        Swal.fire('Terhapus!', 'Data order berhasil dihapus.', 'success');
    } catch (error) {
        console.error('Delete error:', error);
        Swal.fire('Error', 'Gagal menghapus data.', 'error');
    }
}
