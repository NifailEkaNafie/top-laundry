<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Laundry Online | Top Laundry</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .pattern-bg {
            background-color: #f3f4f6;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e5e7eb' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="pattern-bg min-h-screen flex items-center justify-center p-4 lg:p-8">

    <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">

        <div class="hidden lg:flex lg:w-5/12 bg-blue-600 text-white p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-blue-500 opacity-50 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-blue-700 opacity-50 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-blue-600 font-bold text-xl">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-wide">Top Laundry</h1>
                </div>

                <h2 class="text-4xl font-bold mb-6 leading-tight">Cucian Bersih,<br>Hidup Lebih Mudah.</h2>
                <p class="text-blue-100 text-lg mb-8">Booking layanan laundry premium kami sekarang. Kami jemput, cuci, dan antar kembali ke depan pintu Anda.</p>

                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500/30 flex items-center justify-center">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Antar Jemput Gratis</h4>
                            <p class="text-sm text-blue-100">Untuk radius 5km</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500/30 flex items-center justify-center">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Pengerjaan Cepat</h4>
                            <p class="text-sm text-blue-100">Bisa selesai dalam 24 jam</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-12">
                <p class="text-sm opacity-70">© 2025 Top Laundry Indonesia</p>
            </div>
        </div>

        <div class="w-full lg:w-7/12 bg-white p-8 lg:p-12">
            <div class="lg:hidden flex items-center gap-2 mb-6">
                <i class="fa-solid fa-shirt text-blue-600 text-xl"></i>
                <h1 class="text-2xl font-bold text-gray-800">Top Laundry</h1>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Formulir Pemesanan</h2>
            <p class="text-gray-500 mb-8">Isi data diri Anda untuk memulai pesanan.</p>

            <form id="bookingForm" class="space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="name" name="name" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none bg-gray-50 focus:bg-white"
                            placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-brands fa-whatsapp text-gray-400"></i>
                            </div>
                            <input type="tel" id="phone" name="phone" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none bg-gray-50 focus:bg-white"
                                placeholder="08123xxxx">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Layanan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-list-check text-gray-400"></i>
                            </div>
                            <select id="service_type" name="service_type" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                <option value="">Pilih Layanan...</option>
                                <option value="cuci-kering">Cuci Kering (Rp 5rb/kg)</option>
                                <option value="cuci-setrika">Cuci Setrika (Rp 8rb/kg)</option>
                                <option value="cuci-basah">Cuci Basah (Rp 4rb/kg)</option>
                                <option value="setrika-saja">Setrika Saja (Rp 4rb/kg)</option>
                                <option value="dry-clean">Dry Clean (Rp 15rb/kg)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Penjemputan</label>
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                        </div>
                        <textarea id="address" name="address" rows="3" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none bg-gray-50 focus:bg-white resize-none"
                            placeholder="Jalan, Nomor Rumah, Patokan, dll..."></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <input type="text" id="notes" name="notes"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none bg-gray-50 focus:bg-white"
                        placeholder="Misal: Jangan dicampur pakaian putih">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Kirim Pesanan</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-500">
                    Apakah Anda Admin? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login Disini</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // UI Loading
            const btn = this.querySelector('button[type="submit"]');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            const formData = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                service_type: document.getElementById('service_type').value,
                notes: document.getElementById('notes').value
            };

            try {
                // Post ke API Public
                await axios.post('/api/public/booking', formData);

                // Success Alert
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan Diterima!',
                    text: 'Tim kami akan segera menghubungi Anda untuk konfirmasi.',
                    confirmButtonText: 'Oke, Terima Kasih',
                    confirmButtonColor: '#2563EB',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-2'
                    }
                });

                this.reset();

            } catch (error) {
                console.error(error);
                let msg = 'Terjadi kesalahan pada sistem.';
                if(error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg,
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                // Reset Button
                btn.disabled = false;
                btn.innerHTML = originalContent;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
