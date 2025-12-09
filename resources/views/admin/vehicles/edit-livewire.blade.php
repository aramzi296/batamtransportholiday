@extends('layouts.admin')

@section('title', 'Edit Kendaraan')
@section('page-title', 'Edit Kendaraan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Edit Kendaraan</h5>
    </div>
    <div class="card-body">
        @livewire('admin.vehicle-form', ['vehicleId' => $vehicle->id])
    </div>
</div>
@endsection

