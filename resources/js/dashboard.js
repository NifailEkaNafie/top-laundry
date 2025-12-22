// Ensure Axios is available (it should be bundled by default in Laravel with Vite)
// import axios from 'axios'; // If not globally available, uncomment this line.

document.addEventListener('DOMContentLoaded', function () {
    const ordersTableBody = document.getElementById('orders-table-body');
    const statsContainer = document.getElementById('stats-container');
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
    const orderForm = document.getElementById('orderForm');
    const orderModalLabel = document.getElementById('orderModalLabel');
    const addOrderBtn = document.getElementById('addOrderBtn');
    const customerSelect = document.getElementById('customer_id');

    let editingOrderId = null;

    // Helper to get JWT token
    function getToken() {
        return localStorage.getItem('jwt_token');
    }

    // Helper for API requests
    async function apiRequest(method, url, data = null) {
        const token = getToken();
        if (!token) {
            alert('Sesi Anda telah berakhir. Silakan login kembali.');
            window.location.href = '/login'; // Redirect to login
            return null;
        }

        try {
            const config = {
                method: method,
                url: `/api${url}`,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                data: data
            };
            const response = await axios(config);
            return response.data;
        } catch (error) {
            console.error('API Request Error:', error.response || error);
            if (error.response && error.response.status === 401) {
                alert('Token tidak valid atau kadaluarsa. Silakan login kembali.');
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            } else {
                alert('Terjadi kesalahan: ' + (error.response?.data?.message || error.message));
            }
            return null;
        }
    }

    // Load Customers for dropdown
    async function loadCustomers() {
        const data = await apiRequest('GET', '/customers');
        if (data) {
            customerSelect.innerHTML = '<option value="">Pilih Customer</option>';
            data.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.id;
                option.textContent = customer.name;
                customerSelect.appendChild(option);
            });
        }
    }

    // Load Orders and Stats
    async function loadDashboardData() {
        // Load stats
        // Assuming an API endpoint for stats like /api/dashboard/stats
        // For now, let's just show dummy stats or derive from orders if no specific endpoint
        statsContainer.innerHTML = `
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #667eea;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Pesanan Diproses</h5>
                                <p class="card-text fs-1 fw-bold" id="stats-proses">0</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.5A4.5 4.5 0 0 0 8 3zM3.5 9A4.5 4.5 0 0 0 8 13c1.552 0 2.94-.707 3.857-1.818a.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Pesanan Selesai</h5>
                                <p class="card-text fs-1 fw-bold" id="stats-selesai">0</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        `;


        const orders = await apiRequest('GET', '/orders');
        if (orders) {
            renderOrdersTable(orders);
            // Update stats based on fetched orders
            const prosesCount = orders.filter(order => order.status === 'Di Proses').length;
            const selesaiCount = orders.filter(order => order.status === 'Selesai').length;
            document.getElementById('stats-proses').textContent = prosesCount;
            document.getElementById('stats-selesai').textContent = selesaiCount;
        } else {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                        </svg>
                        <p class="mt-2 mb-0">Belum ada data pesanan saat ini.</p>
                    </td>
                </tr>
            `;
        }
    }

    function renderOrdersTable(orders) {
        ordersTableBody.innerHTML = '';
        if (orders.length === 0) {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                        </svg>
                        <p class="mt-2 mb-0">Belum ada data pesanan saat ini.</p>
                    </td>
                </tr>
            `;
            return;
        }

        orders.forEach(order => {
            const row = ordersTableBody.insertRow();
            let statusBadgeClass;
            switch (order.status) {
                case 'Selesai':
                    statusBadgeClass = 'bg-success';
                    break;
                case 'Di Proses':
                    statusBadgeClass = 'bg-warning text-dark';
                    break;
                case 'Baru':
                    statusBadgeClass = 'bg-info text-dark';
                    break;
                case 'pending': // Ensure 'pending' is also handled
                    statusBadgeClass = 'bg-secondary';
                    break;
                default:
                    statusBadgeClass = 'bg-secondary';
                    break;
            }

            row.innerHTML = `
                <td class="text-center">#${order.id}</td>
                <td>${order.customer ? order.customer.name : order.customer_name || 'N/A'}</td>
                <td><span class="badge bg-light text-dark">${order.service_type}</span></td>
                <td class="text-center">${order.weight_kg} Kg</td>
                <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(order.price_total)}</td>
                <td class="text-center"><span class="badge rounded-pill ${statusBadgeClass}">${order.status}</span></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info edit-btn" data-id="${order.id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${order.id}">Hapus</button>
                </td>
            `;

            row.querySelector('.edit-btn').addEventListener('click', () => editOrder(order.id));
            row.querySelector('.delete-btn').addEventListener('click', () => deleteOrder(order.id));
        });
    }

    // Edit Order
    async function editOrder(id) {
        editingOrderId = id;
        orderModalLabel.textContent = 'Edit Order';
        document.getElementById('orderMethod').value = 'PUT';

        const order = await apiRequest('GET', `/orders/${id}`);
        if (order) {
            document.getElementById('orderId').value = order.id;
            document.getElementById('customer_id').value = order.customer_id;
            document.getElementById('service_type').value = order.service_type;
            document.getElementById('weight_kg').value = order.weight_kg;
            document.getElementById('price_total').value = order.price_total;
            document.getElementById('status').value = order.status;
            orderModal.show();
        }
    }

    // Delete Order
    async function deleteOrder(id) {
        if (confirm('Apakah Anda yakin ingin menghapus order ini?')) {
            const result = await apiRequest('DELETE', `/orders/${id}`);
            if (result) {
                alert(result.message);
                loadDashboardData();
            }
        }
    }

    // Handle form submission for Add/Edit
    orderForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(orderForm);
        const data = Object.fromEntries(formData.entries());

        // Laravel's JWT-auth typically uses `_method` for PUT/PATCH via POST
        const method = document.getElementById('orderMethod').value;
        let result;

        // Validation UI Reset
        orderForm.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.classList.remove('is-invalid');
            input.nextElementSibling.textContent = '';
        });


        if (method === 'POST') {
            result = await apiRequest('POST', '/orders', data);
        } else if (method === 'PUT') {
            result = await apiRequest('PUT', `/orders/${editingOrderId}`, data);
        }

        if (result) {
            alert(result.message);
            orderModal.hide();
            orderForm.reset();
            loadDashboardData();
        } else if (axios.isAxiosError(result) && result.response && result.response.status === 422) {
             // Handle validation errors
             const errors = result.response.data.errors;
             for (const field in errors) {
                 const input = orderForm.querySelector(`[name="${field}"]`);
                 if (input) {
                     input.classList.add('is-invalid');
                     input.nextElementSibling.textContent = errors[field][0];
                 }
             }
        }
    });

    // Reset modal on hide
    orderModal._element.addEventListener('hidden.bs.modal', function () {
        orderForm.reset();
        orderModalLabel.textContent = 'Tambah Order Baru';
        document.getElementById('orderMethod').value = 'POST';
        editingOrderId = null;
        // Clear validation feedback
        orderForm.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.classList.remove('is-invalid');
            if (input.nextElementSibling) {
                input.nextElementSibling.textContent = '';
            }
        });
    });

    // Add Order button click
    addOrderBtn.addEventListener('click', function() {
        orderModalLabel.textContent = 'Tambah Order Baru';
        document.getElementById('orderMethod').value = 'POST';
        editingOrderId = null;
        orderForm.reset();
        orderModal.show();
    });

    // Initial load
    loadCustomers();
    loadDashboardData();
});

// Logout functionality (assuming it's in a global script or app.js)
// If you have a dedicated logout button in app.blade.php that triggers this, it's better.
// For now, let's put a placeholder
document.getElementById('logout-form')?.addEventListener('submit', async function(event) {
    event.preventDefault();
    const token = getToken();
    if (token) {
        // Optionally hit a logout endpoint if your JWT library supports server-side token invalidation
        // await apiRequest('POST', '/logout', {});
        localStorage.removeItem('jwt_token');
        window.location.href = '/login';
    }
});

