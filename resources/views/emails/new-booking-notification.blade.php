<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Baru - {{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px 20px;
        }
        .booking-details {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #dc3545;
        }
        .vehicle-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .customer-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .total-amount {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #155724;
            margin: 20px 0;
        }
        .urgent-action {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn-success {
            background: #28a745;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .priority-high {
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        @media (max-width: 600px) {
            .detail-row {
                flex-direction: column;
            }
            .detail-row .value {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🚨 BOOKING BARU!</h1>
            <p>Segera tindak lanjuti booking customer berikut</p>
            <span class="priority-high">PRIORITAS TINGGI</span>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>🆕 Booking Baru Masuk!</h2>
            
            <p>Ada booking baru yang memerlukan perhatian dan tindak lanjut dari tim customer service.</p>

            <!-- Quick Info -->
            <div class="urgent-action">
                <h3 style="margin-top: 0; color: #721c24;">⏰ AKSI DIPERLUKAN</h3>
                <p><strong>Hubungi customer dalam 1x24 jam untuk konfirmasi booking!</strong></p>
                <a href="tel:{{ $booking->customer_phone }}" class="btn">📞 Telepon Sekarang</a>
                <a href="mailto:{{ $booking->customer_email }}" class="btn btn-success">📧 Kirim Email</a>
            </div>

            <!-- Booking Summary -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #dc3545;">📋 Ringkasan Booking</h3>
                
                <div class="detail-row">
                    <span class="label">Kode Booking:</span>
                    <span class="value"><strong>{{ $booking->booking_code }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Tanggal Booking:</span>
                    <span class="value">{{ $booking->created_at->format('d F Y, H:i') }} WIB</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Periode Rental:</span>
                    <span class="value">{{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Durasi:</span>
                    <span class="value">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} hari</span>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="customer-info">
                <h3 style="margin-top: 0; color: #0277bd;">👤 Informasi Customer</h3>
                
                <div class="detail-row">
                    <span class="label">Nama Lengkap:</span>
                    <span class="value"><strong>{{ $booking->customer_name }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Email:</span>
                    <span class="value">
                        <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Telepon:</span>
                    <span class="value">
                        <a href="tel:{{ $booking->customer_phone }}">{{ $booking->customer_phone }}</a>
                    </span>
                </div>
                
                @if($booking->notes)
                <div class="detail-row">
                    <span class="label">Catatan Customer:</span>
                    <span class="value">"{{ $booking->notes }}"</span>
                </div>
                @endif
            </div>

            <!-- Vehicle Details -->
            <div class="vehicle-info">
                <h3 style="margin-top: 0; color: #856404;">🚙 Detail Kendaraan</h3>
                
                <div class="detail-row">
                    <span class="label">Kendaraan:</span>
                    <span class="value"><strong>{{ $booking->vehicle->name }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Kategori:</span>
                    <span class="value">{{ $booking->vehicle->category->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Spesifikasi:</span>
                    <span class="value">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Kapasitas:</span>
                    <span class="value">{{ $booking->vehicle->seats }} kursi</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Harga/Hari:</span>
                    <span class="value">Rp {{ number_format($booking->vehicle->price_per_day, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="total-amount">
                💰 Total Pendapatan: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </div>

            <!-- Action Items -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #dc3545;">✅ Checklist Tindak Lanjut</h3>
                <ul style="line-height: 2;">
                    <li>☐ Hubungi customer untuk konfirmasi booking</li>
                    <li>☐ Verifikasi ketersediaan kendaraan pada tanggal tersebut</li>
                    <li>☐ Konfirmasi detail pickup dan return</li>
                    <li>☐ Jelaskan persyaratan dokumen (KTP, SIM, dll.)</li>
                    <li>☐ Diskusikan metode pembayaran</li>
                    <li>☐ Update status booking di sistem</li>
                    <li>☐ Kirim konfirmasi final ke customer</li>
                </ul>
            </div>

            <!-- Quick Actions -->
            <div style="text-align: center; margin: 30px 0;">
                <h4>Aksi Cepat:</h4>
                <a href="{{ url('/admin/bookings/' . $booking->id) }}" class="btn btn-success">
                    📊 Lihat di Admin Panel
                </a>
                <a href="tel:{{ $booking->customer_phone }}" class="btn">
                    📞 Telepon Customer
                </a>
                <a href="mailto:{{ $booking->customer_email }}?subject=Konfirmasi Booking {{ $booking->booking_code }}" class="btn">
                    📧 Email Customer
                </a>
            </div>

            <!-- Important Notes -->
            <div style="background: #e2e3e5; border: 1px solid #d1ecf1; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #0c5460;">📝 Catatan Penting:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Prioritaskan booking ini - customer menunggu konfirmasi</li>
                    <li>Pastikan kendaraan tersedia pada periode yang diminta</li>
                    <li>Jika kendaraan tidak tersedia, tawarkan alternatif</li>
                    <li>Selalu berikan pelayanan terbaik untuk kepuasan customer</li>
                </ul>
            </div>

            <hr>
            
            <p><strong>Tim Customer Service,</strong></p>
            <p>Harap segera follow up booking ini dan update statusnya di sistem admin panel.</p>
            
            <p>Terima kasih!</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Car Rental Service - Customer Service Alert</strong></p>
            <p>📧 Admin Panel: <a href="{{ url('/admin') }}">{{ url('/admin') }}</a></p>
            <p>📞 Hotline CS: (021) 1234-5678</p>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">
            <p style="font-size: 12px; color: #adb5bd;">
                Email notifikasi otomatis untuk tim customer service.<br>
                Booking ID: {{ $booking->id }} | Timestamp: {{ $booking->created_at->format('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>