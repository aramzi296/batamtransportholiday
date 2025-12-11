@extends('layouts.admin')

@section('title', 'Detail Booking - ' . $booking->booking_code)
@section('page-title', 'Detail Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Detail Booking</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Booking</a></li>
                <li class="breadcrumb-item active">{{ $booking->booking_code }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if($booking->status === 'pending')
            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi booking ini?')">
                    <i class="fas fa-check"></i> Konfirmasi
                </button>
            </form>
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan booking ini?')">
                    <i class="fas fa-times"></i> Batalkan
                </button>
            </form>
        @endif
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Booking Information -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Booking</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Kode Booking:</strong></td>
                                <td>{{ $booking->booking_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="badge bg-warning fs-6">Pending</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-success fs-6">Dikonfirmasi</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary fs-6">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger fs-6">Dibatalkan</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Booking:</strong></td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Mulai:</strong></td>
                                <td>{{ $booking->start_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai:</strong></td>
                                <td>{{ $booking->end_date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Durasi:</strong></td>
                                <td>{{ $booking->total_days }} hari</td>
                            </tr>
                            <tr>
                                <td><strong>Harga per Hari:</strong></td>
                                <td>Rp {{ number_format($booking->daily_price) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Harga:</strong></td>
                                <td><h5 class="text-primary">Rp {{ number_format($booking->total_price) }}</h5></td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui:</strong></td>
                                <td>{{ $booking->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($booking->notes)
                <div class="mt-3">
                    <strong>Catatan Pelanggan:</strong>
                    <p class="mt-2 p-3 bg-light rounded">{{ $booking->notes }}</p>
                </div>
                @endif

                @if($booking->admin_notes)
                <div class="mt-3">
                    <strong>Catatan Admin:</strong>
                    <p class="mt-2 p-3 bg-warning bg-opacity-10 rounded">{{ $booking->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Pelanggan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong><br>{{ $booking->customer_name }}</p>
                        <p><strong>Email:</strong><br>{{ $booking->customer_email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Telepon:</strong><br>{{ $booking->customer_phone }}</p>
                        <p><strong>Alamat:</strong><br>{{ $booking->customer_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-car"></i> Informasi Kendaraan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ $booking->vehicle->main_image }}" alt="{{ $booking->vehicle->name }}" 
                             class="img-fluid rounded">
                    </div>
                    <div class="col-md-9">
                        <h5>{{ $booking->vehicle->name }}</h5>
                        <p class="text-muted">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Plat Nomor:</strong> {{ $booking->vehicle->plate_number }}</p>
                                <p><strong>Warna:</strong> {{ $booking->vehicle->color }}</p>
                                <p><strong>Kursi:</strong> {{ $booking->vehicle->seats }} kursi</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Transmisi:</strong> {{ $booking->vehicle->transmission }}</p>
                                <p><strong>Bahan Bakar:</strong> {{ $booking->vehicle->fuel_type }}</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($booking->vehicle->price_per_day) }}/hari</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions & Update Status -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Update Harga, Status & Catatan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Harga per Hari</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="daily_price" step="0.01" min="0" class="form-control"
                                   value="{{ old('daily_price', $booking->daily_price) }}" id="dailyPriceInput">
                        </div>
                        <small class="text-muted">Total hari: {{ $booking->total_days }} hari</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <div class="form-control bg-light">
                            <strong id="totalPriceDisplay">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Booking</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="4" 
                                  placeholder="Tambahkan catatan untuk booking ini...">{{ $booking->admin_notes }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Update
                    </button>
                </form>
            </div>
        </div>

        <!-- Manual Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-paper-plane"></i> Kirim Notifikasi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.bookings.send-confirmation-email', $booking) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm w-100" onclick="return confirm('Kirim email konfirmasi ke customer?')">
                            <i class="fas fa-envelope"></i> Kirim Email Konfirmasi
                        </button>
                    </form>
                    <form action="{{ route('admin.bookings.send-confirmation-whatsapp', $booking) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Kirim WhatsApp konfirmasi ke customer?')">
                            <i class="fab fa-whatsapp"></i> Kirim WhatsApp Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $booking->customer_email }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope"></i> Email Pelanggan
                    </a>
                    <a href="tel:{{ $booking->customer_phone }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone"></i> Telepon Pelanggan
                    </a>
                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $booking->customer_phone) }}" 
                       class="btn btn-outline-success btn-sm" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="{{ route('admin.vehicles.show', $booking->vehicle) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-car"></i> Detail Kendaraan
                    </a>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> History Booking</h5>
            </div>
            <div class="card-body">
                @if($booking->events && $booking->events->count() > 0)
                <div class="timeline">
                    @foreach($booking->events as $event)
                    <div class="timeline-item">
                        <div class="timeline-marker 
                            @if($event->event_type === 'customer_submit') bg-primary
                            @elseif($event->event_type === 'email_sent_customer' || $event->event_type === 'email_confirmation_sent') bg-info
                            @elseif($event->event_type === 'email_sent_admin') bg-secondary
                            @elseif($event->event_type === 'whatsapp_sent_admin' || $event->event_type === 'whatsapp_confirmation_sent') bg-success
                            @elseif($event->event_type === 'status_changed') bg-warning
                            @else bg-secondary
                            @endif
                        "></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">
                                @switch($event->event_type)
                                    @case('customer_submit')
                                        <i class="fas fa-user"></i> Customer Mengirim Booking
                                        @break
                                    @case('email_sent_customer')
                                        <i class="fas fa-envelope"></i> Email Dikirim ke Customer
                                        @break
                                    @case('email_sent_admin')
                                        <i class="fas fa-envelope"></i> Email Dikirim ke Admin
                                        @break
                                    @case('whatsapp_sent_admin')
                                        <i class="fab fa-whatsapp"></i> WhatsApp Dikirim ke Admin
                                        @break
                                    @case('status_changed')
                                        <i class="fas fa-exchange-alt"></i> Status Diubah
                                        @break
                                    @case('email_confirmation_sent')
                                        <i class="fas fa-envelope"></i> Email Konfirmasi Dikirim
                                        @break
                                    @case('whatsapp_confirmation_sent')
                                        <i class="fab fa-whatsapp"></i> WhatsApp Konfirmasi Dikirim
                                        @break
                                    @default
                                        {{ $event->event_type }}
                                @endswitch
                            </h6>
                            @if($event->description)
                                <p class="timeline-text mb-1">{{ $event->description }}</p>
                            @endif
                            <p class="timeline-text text-muted small">
                                {{ $event->created_at->format('d/m/Y H:i:s') }}
                                @if($event->user)
                                    | oleh {{ $event->user->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center">Belum ada history</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .timeline-title {
        font-size: 14px;
        margin-bottom: 5px;
        color: #495057;
    }
    
    .timeline-text {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        const dailyInput = document.getElementById('dailyPriceInput');
        const totalDisplay = document.getElementById('totalPriceDisplay');
        const totalDays = {{ $booking->total_days }};

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function updateTotal() {
            const val = parseFloat(dailyInput.value);
            if (!isNaN(val)) {
                const total = val * totalDays;
                totalDisplay.textContent = 'Rp ' + formatRupiah(total);
            }
        }

        if (dailyInput) {
            dailyInput.addEventListener('input', updateTotal);
            updateTotal();
        }
    })();
</script>
@endpush