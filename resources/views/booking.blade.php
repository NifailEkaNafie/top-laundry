<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Laundry Online | Top Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-blue-50 text-gray-800">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">

            <div class="text-center">
                <h1 class="text-3xl font-bold text-blue-600">🧺 Top Laundry</h1>
                <h2 class="mt-2 text-xl font-semibold text-gray-900">Form Booking Online</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Isi data diri Anda, kami akan menjemput/memproses cucian Anda.
                </p>
            </div>

            <form class="mt-8 space-y-6" id="bookingForm">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input id="phone" name="phone" type="tel" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: 08123456789">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Nama Jalan, Nomor Rumah, Patokan..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Layanan</label>
                        <select id="service_type" name="service_type" required class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Layanan</option>
                            <option value="cuci-kering">Cuci Kering</option>
                            <option value="cuci-setrika">Cuci Setrika</option>
                            <option value="cuci-basah">Cuci Basah</option>
                            <option value="setrika-saja">Setrika Saja</option>
                            <option value="dry-clean">Dry Clean</option>
                        </select>
                    </div>

                    <div class="mb-4">
                         <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                         <input id="notes" name="notes" type="text" class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Misal: Jangan dicampur warna putih">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Kirim Pesanan
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">Login Admin</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Ubah tombol jadi loading
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            // Ambil data
            const formData = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                service_type: document.getElementById('service_type').value,
                notes: document.getElementById('notes').value
            };

            try {
                // Kirim ke API Public
                const response = await axios.post('/api/public/booking', formData);

                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan Diterima!',
                    text: 'Tim kami akan segera memproses pesanan Anda.',
                    confirmButtonColor: '#2563EB'
                });

                this.reset(); // Reset form

            } catch (error) {
                console.error(error);
                let msg = 'Terjadi kesalahan sistem.';
                if(error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                Swal.fire('Gagal', msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    </script>
</body>
</html>
