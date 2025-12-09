@extends('layouts.app')

@section('title', 'Booking Kendaraan')

@section('content')
<div class="container py-5">
    @livewire('booking-form', ['vehicleId' => request()->get('vehicle_id')])
</div>
@endsection
