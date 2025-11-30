@extends('layouts.app')

@section('title', 'Syarat dan Ketentuan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-contract"></i> Syarat dan Ketentuan</h4>
                </div>
                <div class="card-body">
                    <h5>1. Umum</h5>
                    <ul>
                        <li>Penyewa wajib memiliki KTP dan SIM yang masih berlaku.</li>
                        <li>Kendaraan hanya boleh digunakan sesuai perjanjian dan tidak untuk tindakan melanggar hukum.</li>
                        <li>Penyewa bertanggung jawab atas kerusakan atau kehilangan kendaraan selama masa sewa.</li>
                    </ul>
                    <h5>2. Pembayaran</h5>
                    <ul>
                        <li>Pembayaran dilakukan di awal sesuai tarif yang telah disepakati.</li>
                        <li>Deposit akan dikembalikan setelah kendaraan dikembalikan dalam kondisi baik.</li>
                    </ul>
                    <h5>3. Pembatalan</h5>
                    <ul>
                        <li>Pembatalan sewa harus dilakukan minimal 24 jam sebelum waktu mulai sewa.</li>
                        <li>Biaya pembatalan dapat dikenakan sesuai kebijakan perusahaan.</li>
                    </ul>
                    <h5>4. Lain-lain</h5>
                    <ul>
                        <li>Perusahaan berhak menolak penyewaan jika ditemukan pelanggaran syarat dan ketentuan.</li>
                        <li>Segala perselisihan akan diselesaikan secara musyawarah.</li>
                    </ul>
                    <hr>
                    <p class="text-muted small">Syarat dan ketentuan ini dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection