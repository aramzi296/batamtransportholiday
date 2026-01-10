@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Hubungi Kami</h1>
                <p class="lead mb-4">Kami siap membantu Anda dengan layanan rental terbaik. Jangan ragu untuk menghubungi tim customer service kami.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-phone fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h3 class="mb-4">
                        <i class="fas fa-envelope me-3 text-primary"></i>Kirim Pesan
                    </h3>
                    <p class="text-muted mb-4">Isi form di bawah ini dan kami akan merespons pesan Anda dalam waktu 24 jam.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="alamat@email.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-phone text-primary"></i>
                                    </span>
                                    <input type="tel" class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label fw-semibold">Subjek *</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Pilih subjek</option>
                                    <option value="booking" {{ old('subject') == 'booking' ? 'selected' : '' }}>Pertanyaan Booking</option>
                                    <option value="pricing" {{ old('subject') == 'pricing' ? 'selected' : '' }}>Informasi Harga</option>
                                    <option value="vehicle" {{ old('subject') == 'vehicle' ? 'selected' : '' }}>Informasi Kendaraan</option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Keluhan</option>
                                    <option value="suggestion" {{ old('subject') == 'suggestion' ? 'selected' : '' }}>Saran</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Pesan *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" 
                                          placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1"
                                           {{ old('newsletter') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newsletter">
                                        Saya ingin mendapatkan newsletter dan penawaran khusus
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-4">
            <!-- Contact Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">
                        <i class="fas fa-map-marker-alt me-3 text-primary"></i>Informasi Kontak
                    </h4>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Telepon</h6>
                                <p class="text-muted mb-0">+62 821 7086 0825</p>
                                <p class="text-muted mb-0">+62 813 6481 0770</p>
                                <p class="text-muted mb-0">+62 811 700 7201</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fab fa-whatsapp text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">WhatsApp</h6>
                                <p class="text-muted mb-0">+62 821 7086 0825</p>
                                <p class="text-muted mb-0">+62 813 6481 0770</p>
                                <p class="text-muted mb-0">+62 811 700 7201</p>
                                <a href="https://wa.me/6282170860825" class="btn btn-outline-success btn-sm mt-2" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>Chat Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">info@dsarana.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <div class="contact-icon me-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Alamat</h6>
                                <p class="text-muted mb-0">
                                    Mall Top 100 Tembesi Blok H3 No. 1<br>
                                    Batam, Indonesia
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-4">
                        <i class="fas fa-clock me-3 text-primary"></i>Jam Operasional
                    </h5>
                    
                    <div class="business-hours">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Senin - Jumat</span>
                            <span class="fw-semibold">08:00 - 20:00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sabtu</span>
                            <span class="fw-semibold">08:00 - 18:00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Minggu</span>
                            <span class="fw-semibold">09:00 - 17:00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Hari Libur</span>
                            <span class="fw-semibold text-muted">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-4">Ikuti Kami</h5>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-primary btn-social">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-social">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-social">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-dark btn-social">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="map-container">
                        <iframe 
                        
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.160570649037!2d104.0015280757133!3d1.0403928624908434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98de3cdae0bed%3A0xaad20e034c1380ee!2sRumah%20Kopi%20Batam%20RKB!5e0!3m2!1sen!2sid!4v1764462441232!5m2!1sen!2sid"
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Kunjungi kantor kami di Jl. Sudirman No. 123, Jakarta Pusat
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-center mb-5">Pertanyaan yang Sering Diajukan</h3>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    Bagaimana cara melakukan booking?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda bisa melakukan booking melalui website kami dengan memilih kendaraan, mengisi tanggal rental, 
                                    dan melengkapi data diri. Tim kami akan mengkonfirmasi dalam 24 jam.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Apakah ada deposit yang harus dibayar?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, kami memerlukan deposit yang akan dikembalikan setelah kendaraan dikembalikan 
                                    dalam kondisi baik. Besaran deposit berbeda-beda sesuai tipe kendaraan.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Dokumen apa saja yang diperlukan?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda perlu membawa KTP asli, SIM yang masih berlaku, dan kartu kredit/debit 
                                    untuk jaminan. Pastikan semua dokumen dalam kondisi baik dan tidak expired.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 50%;
    }
    
    .contact-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .contact-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .business-hours {
        font-size: 0.9rem;
    }
    
    .btn-social {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .btn-social:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #1e7e34;
        box-shadow: 0 0 0 0.2rem rgba(30, 126, 52, 0.25);
    }
    
    .input-group-text {
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 600;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
        border: none;
        padding: 12px 24px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1a6e2d 0%, #12401a 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 126, 52, 0.4);
    }
    
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    
    .accordion-button {
        border-radius: 12px !important;
        font-weight: 600;
    }
    
    .accordion-item {
        border-radius: 12px !important;
    }
    
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
    }
    
    /* Alert styles for form feedback */
    .alert-success {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .alert-danger {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
// Form submission with loading state
document.getElementById('contactForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    submitBtn.disabled = true;
    
    // Re-enable button after a delay if form submission fails
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000); // 10 seconds timeout
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
    
    // Smooth scroll to alerts if they exist
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
    
    // Format Indonesian phone number
    if (value.startsWith('62')) {
        value = '+' + value;
    } else if (value.startsWith('0')) {
        // Keep as is for local format
    } else if (value.length > 0) {
        value = '0' + value;
    }
    
    e.target.value = value;
});

// Character counter for message
const messageTextarea = document.getElementById('message');
const maxLength = 2000;

// Create counter element
const counterDiv = document.createElement('div');
counterDiv.className = 'text-end mt-2 text-muted small';
counterDiv.innerHTML = `<span id="charCount">0</span>/${maxLength} karakter`;
messageTextarea.parentNode.appendChild(counterDiv);

// Update counter
messageTextarea.addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
    
    // Change color when approaching limit
    if (charCount > maxLength * 0.9) {
        counterDiv.className = 'text-end mt-2 text-warning small';
    } else if (charCount > maxLength * 0.95) {
        counterDiv.className = 'text-end mt-2 text-danger small';
    } else {
        counterDiv.className = 'text-end mt-2 text-muted small';
    }
});
</script>
@endpush