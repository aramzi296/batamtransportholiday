@extends('layouts.app')

@section('title', 'Login Member')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Login Member</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('member.login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        <a href="{{ route('password.request') }}" class="text-warning">Lupa Password?</a>
                    </p>
                    <p class="mb-0 mt-2">
                        Belum punya akun member? 
                        <a href="{{ route('member.register') }}" class="text-primary">Daftar sekarang</a>
                    </p>
                    <p class="mb-0 mt-2">
                        <a href="{{ route('login') }}" class="text-muted">Login sebagai Customer/Admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection









