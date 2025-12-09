@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')
<!-- Vehicles Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Armada D'Sarana</h2>
            <p class="lead">Pilih kendaraan yang sesuai dengan kebutuhan perjalanan Anda</p>
        </div>
        
        @livewire('vehicles-list')
    </div>
</section>
@endsection

