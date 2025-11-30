@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user"></i> Profil Saya</h4>
                </div>
                <div class="card-body">
                    <p class="lead">Halaman profil pengguna. Silakan custom sesuai kebutuhan Anda.</p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Nama:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li class="list-group-item"><strong>Role:</strong> {{ Auth::user()->role }}</li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="{{ route('password.change.form') }}" class="btn btn-info">
                            <i class="fas fa-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection