<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Email Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .config-card {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .config-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        .config-item:last-child {
            border-bottom: none;
        }
        .config-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }
        .config-value {
            color: #212529;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-envelope"></i> Test Pengiriman Email</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Halaman ini digunakan untuk menguji konfigurasi email yang telah diatur di file <code>.env</code>.</p>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-cog"></i> Konfigurasi Email dari .env</h6>
                    </div>
                    <div class="card-body config-card">
                        <div class="config-item d-flex">
                            <span class="config-label">Mailer:</span>
                            <span class="config-value">{{ $mailConfig['mailer'] ?? 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">Host:</span>
                            <span class="config-value">{{ $mailConfig['host'] ?? 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">Port:</span>
                            <span class="config-value">{{ $mailConfig['port'] ?? 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">Username:</span>
                            <span class="config-value">{{ $mailConfig['username'] ? '***' . substr($mailConfig['username'], -4) : 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">Encryption:</span>
                            <span class="config-value">{{ $mailConfig['encryption'] ?? 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">From Address:</span>
                            <span class="config-value">{{ $mailConfig['from_address'] ?? 'N/A' }}</span>
                        </div>
                        <div class="config-item d-flex">
                            <span class="config-label">From Name:</span>
                            <span class="config-value">{{ $mailConfig['from_name'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Test Email Form -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-paper-plane"></i> Kirim Test Email</h6>
                    </div>
                    <div class="card-body">
                        <form id="testEmailForm">
                            @csrf
                            <div class="mb-3">
                                <label for="to" class="form-label">Email Tujuan *</label>
                                <input type="email" class="form-control" id="to" name="to" 
                                       placeholder="contoh@email.com" required>
                                <small class="form-text text-muted">Masukkan alamat email yang akan menerima test email</small>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="Test Email dari DSarana" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required>Ini adalah email test dari sistem DSarana.

Jika Anda menerima email ini, berarti konfigurasi email Anda sudah benar.

Konfigurasi yang digunakan:
- Mailer: {{ $mailConfig['mailer'] ?? 'N/A' }}
- Host: {{ $mailConfig['host'] ?? 'N/A' }}
- Port: {{ $mailConfig['port'] ?? 'N/A' }}
- From: {{ $mailConfig['from_address'] ?? 'N/A' }} ({{ $mailConfig['from_name'] ?? 'N/A' }})

Terima kasih.</textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Kirim Test Email
                            </button>
                        </form>
                        <div id="testResult" class="mt-3"></div>
                    </div>
                </div>

                <!-- Information -->
                <div class="card mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Pastikan konfigurasi email di file <code>.env</code> sudah benar sebelum mengirim test email.</li>
                            <li>Jika menggunakan SMTP, pastikan kredensial (username dan password) sudah benar.</li>
                            <li>Jika menggunakan mailer <code>log</code>, email akan tersimpan di file log Laravel.</li>
                            <li>Jika menggunakan mailer <code>array</code>, email tidak akan benar-benar dikirim.</li>
                            <li>Periksa folder <code>storage/logs</code> jika terjadi error saat pengiriman.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('testEmailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const resultDiv = document.getElementById('testResult');
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Mengirim email...</div>';

            const formData = {
                to: document.getElementById('to').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };

            try {
                const response = await fetch('{{ route("admin.email-test.send") }}', {
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
                            <small>Email dikirim ke: ${data.data.to}<br>
                            Subject: ${data.data.subject}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <strong>Gagal!</strong><br>
                            ${data.message || 'Terjadi kesalahan saat mengirim email'}<br>
                            ${data.error ? '<small>' + data.error + '</small>' : ''}
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
            } finally {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Test Email';
            }
        });
    </script>
</body>
</html>

