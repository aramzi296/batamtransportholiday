<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking - {{ $booking->booking_code }}</title>
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
            background: linear-gradient(135deg, #007bff, #0056b3);
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
            color: #007bff;
        }
        .vehicle-info {
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
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-confirmed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
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
            <h1>🚗 Konfirmasi Booking</h1>
            <p>Terima kasih telah mempercayai layanan rental kami</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $booking->customer_name }}</strong>,</p>
            
            <p>Booking kendaraan Anda telah berhasil diterima dengan detail sebagai berikut:</p>

            <!-- Booking Details -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #007bff;">📋 Detail Booking</h3>
                
                <div class="detail-row">
                    <span class="label">Kode Booking:</span>
                    <span class="value"><strong>{{ $booking->booking_code }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Tanggal Booking:</span>
                    <span class="value">{{ $booking->created_at->format('d F Y, H:i') }} WIB</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="status-badge status-{{ $booking->status }}">
                        @if($booking->status == 'pending')
                            Menunggu Konfirmasi
                        @elseif($booking->status == 'confirmed')
                            Dikonfirmasi
                        @else
                            {{ ucfirst($booking->status) }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="vehicle-info">
                <h3 style="margin-top: 0; color: #0277bd;">🚙 Informasi Kendaraan</h3>
                
                <div class="detail-row">
                    <span class="label">Nama Kendaraan:</span>
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
            </div>

            <!-- Rental Period -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #007bff;">📅 Periode Rental</h3>
                
                <div class="detail-row">
                    <span class="label">Tanggal Mulai:</span>
                    <span class="value">{{ $booking->start_date->format('d F Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Tanggal Selesai:</span>
                    <span class="value">{{ $booking->end_date->format('d F Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Durasi:</span>
                    <span class="value">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} hari</span>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="total-amount">
                💰 Total Biaya: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </div>

            <!-- Customer Info -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #007bff;">👤 Informasi Penyewa</h3>
                
                <div class="detail-row">
                    <span class="label">Nama:</span>
                    <span class="value">{{ $booking->customer_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $booking->customer_email }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Telepon:</span>
                    <span class="value">{{ $booking->customer_phone }}</span>
                </div>
                
                @if($booking->notes)
                <div class="detail-row">
                    <span class="label">Catatan:</span>
                    <span class="value">{{ $booking->notes }}</span>
                </div>
                @endif
            </div>

            <!-- Important Notes -->
            <div class="important-note">
                <h4 style="margin-top: 0; color: #856404;">⚠️ Informasi Penting:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Booking Anda saat ini berstatus <strong>{{ $booking->status == 'pending' ? 'menunggu konfirmasi' : $booking->status }}</strong></li>
                    <li>Tim customer service kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi</li>
                    <li>Harap menyiapkan dokumen yang diperlukan (KTP, SIM, dll.)</li>
                    <li>Pembayaran dapat dilakukan saat pengambilan kendaraan atau sesuai kesepakatan</li>
                    <li>Simpan email ini sebagai bukti booking Anda</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" class="btn">
                    🏠 Kembali ke Website
                </a>
            </div>

            <p>Jika ada pertanyaan atau perlu bantuan, jangan ragu untuk menghubungi customer service kami.</p>
            
            <p>Terima kasih atas kepercayaan Anda!</p>
            
            <p>Salam,<br>
            <strong>Tim Car Rental</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Car Rental Service</strong></p>
            <p>📧 Email: info@carrental.com | 📞 Telepon: (021) 1234-5678</p>
            <p>🌐 Website: <a href="{{ url('/') }}">{{ url('/') }}</a></p>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">
            <p style="font-size: 12px; color: #adb5bd;">
                Email ini dikirim secara otomatis. Harap tidak membalas email ini.<br>
                Jika Anda tidak melakukan booking, silakan abaikan email ini.
            </p>
        </div>
    </div>
</body>
</html>