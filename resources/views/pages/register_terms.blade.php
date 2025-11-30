@extends('layouts.app')

@section('title', 'Syarat dan Ketentuan Registrasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-signature"></i> Syarat dan Ketentuan Registrasi</h4>
                </div>
                <div class="card-body">
                    <h5>1. Data Pribadi</h5>
                    <ul>
                        <li>Pengguna wajib mengisi data pribadi yang valid dan dapat dipertanggungjawabkan.</li>
                        <li>Data yang diberikan akan digunakan untuk keperluan administrasi dan layanan rental.</li>
                    </ul>
                    <h5>2. Privasi</h5>
                    <ul>
                        <li>Data pengguna tidak akan dibagikan ke pihak ketiga tanpa izin kecuali untuk keperluan hukum.</li>
                        <li>Pengguna berhak meminta penghapusan data pribadi dari sistem.</li>
                    </ul>
                    <h5>3. Akun</h5>
                    <ul>
                        <li>Pengguna bertanggung jawab atas keamanan akun dan password.</li>
                        <li>Penyalahgunaan akun menjadi tanggung jawab pemilik akun.</li>
                    </ul>
                    <h5>4. Lain-lain</h5>
                    <ul>
                        <li>Pihak D'Sarana berhak menonaktifkan akun jika ditemukan pelanggaran syarat dan ketentuan.</li>
                        <li>Perubahan syarat dan ketentuan akan diinformasikan melalui website.</li>
                    </ul>
                    <hr>
                    <p class="text-muted small">Syarat dan ketentuan registrasi dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection