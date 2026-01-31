@extends('layouts.app')

@section('title', __('messages.terms.title'))

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.terms.title') }}</h1>
                <p class="lead mb-4">{{ __('messages.terms.subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-file-contract fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-3">1. Umum</h5>
                    <ul class="mb-4">
                        <li>Penyewa wajib memiliki KTP dan SIM yang masih berlaku.</li>
                        <li>Kendaraan hanya boleh digunakan sesuai perjanjian dan tidak untuk tindakan melanggar hukum.</li>
                        <li>Penyewa bertanggung jawab atas kerusakan atau kehilangan kendaraan selama masa sewa.</li>
                    </ul>
                    <h5 class="fw-bold mb-3">2. Pembayaran</h5>
                    <ul class="mb-4">
                        <li>Pembayaran dilakukan di awal sesuai tarif yang telah disepakati.</li>
                        <li>Deposit akan dikembalikan setelah kendaraan dikembalikan dalam kondisi baik.</li>
                    </ul>
                    <h5 class="fw-bold mb-3">3. Pembatalan</h5>
                    <ul class="mb-4">
                        <li>Pembatalan sewa harus dilakukan minimal 24 jam sebelum waktu mulai sewa.</li>
                        <li>Biaya pembatalan dapat dikenakan sesuai kebijakan perusahaan.</li>
                    </ul>
                    <h5 class="fw-bold mb-3">4. Lain-lain</h5>
                    <ul class="mb-4">
                        <li>Perusahaan berhak menolak penyewaan jika ditemukan pelanggaran syarat dan ketentuan.</li>
                        <li>Segala perselisihan akan diselesaikan secara musyawarah.</li>
                    </ul>
                    <hr>
                    <p class="text-muted small mb-0">Syarat dan ketentuan ini dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    .card-body ul {
        padding-left: 1.5rem;
    }
    .card-body li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection
