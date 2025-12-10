<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test WhatsApp API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fab fa-whatsapp"></i> Test WhatsApp API</h5>
                    </div>
                    <div class="card-body">
                        <!-- Test Send Booking Message -->
                        <div class="mb-4">
                            <h6 class="mb-3"><i class="fas fa-paper-plane"></i> Test Kirim Pesan Booking</h6>
                            <form id="bookingForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="booking_code" class="form-label">Kode Booking *</label>
                                    <input type="text" class="form-control" id="booking_code" name="booking_code" 
                                           placeholder="Contoh: DS202412101234" required>
                                    <small class="form-text text-muted">Masukkan kode booking yang valid</small>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Nomor Telepon (Opsional)</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                           placeholder="Contoh: 08117007201 atau 628117007201">
                                    <small class="form-text text-muted">Jika kosong, akan menggunakan nomor dari booking</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                                </button>
                            </form>
                            <div id="bookingResult" class="mt-3"></div>
                        </div>

                        <hr>

                        <!-- Test Send Custom Message -->
                        <div>
                            <h6 class="mb-3"><i class="fas fa-comment"></i> Test Kirim Pesan Custom</h6>
                            <form id="testForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon *</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           placeholder="Contoh: 08117007201 atau 628117007201" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" 
                                              placeholder="Pesan test dari sistem DSarana">Halo, ini adalah pesan test dari sistem DSarana.</textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i> Kirim Test
                                </button>
                            </form>
                            <div id="testResult" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Documentation -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Dokumentasi API</h6>
                    </div>
                    <div class="card-body">
                        <h6>1. Kirim Pesan Booking</h6>
                        <pre class="bg-light p-3 rounded"><code>POST /api/whatsapp/send-booking
Content-Type: application/json

{
    "booking_code": "DS202412101234",
    "phone_number": "08117007201" // Optional
}</code></pre>

                        <h6 class="mt-3">2. Test API</h6>
                        <pre class="bg-light p-3 rounded"><code>POST /api/whatsapp/test
Content-Type: application/json

{
    "phone": "08117007201",
    "message": "Pesan test" // Optional
}</code></pre>

                        <h6 class="mt-3">3. Menggunakan cURL</h6>
                        <pre class="bg-light p-3 rounded"><code>curl -X POST http://127.0.0.1:8000/api/whatsapp/send-booking \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {{ csrf_token() }}" \
  -d '{
    "booking_code": "DS202412101234",
    "phone_number": "08117007201"
  }'</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle Booking Form
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const resultDiv = document.getElementById('bookingResult');
            resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Mengirim pesan...</div>';

            const formData = {
                booking_code: document.getElementById('booking_code').value,
                phone_number: document.getElementById('phone_number').value || null
            };

            try {
                const response = await fetch('/api/whatsapp/send-booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong><br>
                            ${data.message}<br>
                            <small>Kode Booking: ${data.data.booking_code}<br>
                            Nomor: ${data.data.phone}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <strong>Gagal!</strong><br>
                            ${data.message || 'Terjadi kesalahan'}
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong><br>
                        ${error.message}
                    </div>
                `;
            }
        });

        // Handle Test Form
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const resultDiv = document.getElementById('testResult');
            resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Mengirim pesan...</div>';

            const formData = {
                phone: document.getElementById('phone').value,
                message: document.getElementById('message').value || null
            };

            try {
                const response = await fetch('/api/whatsapp/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong><br>
                            Pesan berhasil dikirim ke ${formData.phone}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <strong>Gagal!</strong><br>
                            ${data.error || data.message || 'Terjadi kesalahan'}
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong><br>
                        ${error.message}
                    </div>
                `;
            }
        });
    </script>
</body>
</html>

