@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')
@section('page-title', 'Tambah Kendaraan Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Kendaraan</h5>
    </div>
    <div class="card-body">
        @livewire('admin.vehicle-form')
    </div>
</div>
@endsection

