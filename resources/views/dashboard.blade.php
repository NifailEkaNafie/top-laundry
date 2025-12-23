@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4" id="stats-container">
    {{-- Stats will be loaded here by JavaScript --}}
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Daftar Pesanan Laundry</h3>
            <button type="button" class="btn btn-primary" style="background-color: #667eea; border-color: #667eea;" data-bs-toggle="modal" data-bs-target="#orderModal" id="addOrderBtn">
                + Tambah Order Baru
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th class="text-center">Berat</th>
                        <th class="text-end">Total Harga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    {{-- Orders will be loaded here by JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Tambah Order Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    @csrf
                    <input type="hidden" id="orderId" name="id">
                    <input type="hidden" id="orderMethod" name="_method" value="POST"> {{-- Used for PUT/POST --}}

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select" id="customer_id" name="customer_id" required>
                            <option value="">Pilih Customer</option>
                            {{-- Options will be loaded by JavaScript --}}
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="service_type" class="form-label">Jenis Layanan</label>
                        <select class="form-select" id="service_type" name="service_type" required>
                            <option value="">Pilih Jenis Layanan</option>
                            <option value="cuci-kering">Cuci Kering</option>
                            <option value="cuci-setrika">Cuci Setrika</option>
                            <option value="cuci-basah">Cuci Basah</option>
                            <option value="setrika-saja">Setrika Saja</option>
                            <option value="dry-clean">Dry Clean</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="weight_kg" class="form-label">Berat (Kg)</label>
                        <input type="number" step="0.01" class="form-control" id="weight_kg" name="weight_kg" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="price_total" class="form-label">Total Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control" id="price_total" name="price_total" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="Di Proses">Di Proses</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="orderForm" class="btn btn-primary">Simpan Order</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
