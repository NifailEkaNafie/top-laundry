@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Masuk ke Akun Anda
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Atau
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                daftar akun baru sekarang
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Masuk
                    </button>
                </div>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Bukan Admin?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('booking') }}" class="w-full flex justify-center py-2 px-4 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                        📝 Booking Laundry Disini
                    </a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Hapus alert error lama jika ada
        const oldAlert = this.querySelector('.bg-red-50');
        if(oldAlert) oldAlert.remove();

        try {
            const formData = new FormData(this);
            const response = await axios.post(this.action, formData);

            // PERBAIKAN DI SINI: Cek apakah token ada, bukan cek 'success'
            if (response.data.token) {
                // Simpan token & user
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));

                // Set header default untuk request selanjutnya
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;

                // Redirect ke dashboard
                window.location.href = "{{ route('dashboard') }}";
            }
        } catch (error) {
             let errorMessage = 'Terjadi kesalahan saat login.';
             if (error.response && error.response.data && error.response.data.message) {
                 errorMessage = error.response.data.message;
             }

             // Tampilkan alert error
             const alertHtml = `
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Login Gagal</h3>
                            <div class="mt-2 text-sm text-red-700"><p>${errorMessage}</p></div>
                        </div>
                    </div>
                </div>`;
             this.insertAdjacentHTML('afterbegin', alertHtml);
        }
    });
</script>
@endpush
