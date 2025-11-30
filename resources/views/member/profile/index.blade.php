@extends('layouts.member')

@section('title', 'Profil Anggota')
@section('page-title', 'Profil Anggota')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Profil Anggota</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Foto Profil -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label"><i class="fas fa-image"></i> Foto Profil</label>
                            <div class="mb-3">
                                @if($user->foto_profil)
                                    <img src="{{ $user->foto_profil_url }}" alt="Foto Profil" 
                                         class="img-thumbnail mb-2" style="max-width: 200px; max-height: 200px;" 
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgZHk9Ii4zNWVtIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4=';">
                                    <br>
                                @else
                                    <div class="bg-secondary d-inline-block p-3 mb-2">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                    <br>
                                @endif
                                <input type="file" class="form-control @error('foto_profil') is-invalid @enderror" 
                                       id="foto_profil" name="foto_profil" accept="image/*">
                                @error('foto_profil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Dasar -->
                    <h5 class="mb-3"><i class="fas fa-user"></i> Informasi Dasar</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" disabled>
                            <small class="text-muted">Nama tidak dapat diubah. Silakan ubah di Manajemen Akun.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah. Silakan ubah di Manajemen Akun.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control @error('nomor_whatsapp') is-invalid @enderror" 
                                   id="nomor_whatsapp" name="nomor_whatsapp" 
                                   value="{{ old('nomor_whatsapp', $user->nomor_whatsapp) }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('nomor_whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $dataAnggota['tanggal_lahir'] ?? '') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $dataAnggota['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $dataAnggota['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Alamat -->
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt"></i> Alamat</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="alamat_rumah" class="form-label">Alamat Rumah</label>
                            <textarea class="form-control @error('alamat_rumah') is-invalid @enderror" 
                                      id="alamat_rumah" name="alamat_rumah" rows="3" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat_rumah', $dataAnggota['alamat_rumah'] ?? '') }}</textarea>
                            @error('alamat_rumah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" 
                                   id="kecamatan" name="kecamatan" 
                                   value="{{ old('kecamatan', $dataAnggota['kecamatan'] ?? '') }}" 
                                   placeholder="Masukkan kecamatan">
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kelurahan" class="form-label">Kelurahan</label>
                            <input type="text" class="form-control @error('kelurahan') is-invalid @enderror" 
                                   id="kelurahan" name="kelurahan" 
                                   value="{{ old('kelurahan', $dataAnggota['kelurahan'] ?? '') }}" 
                                   placeholder="Masukkan kelurahan">
                            @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Kategori Anggota -->
                    <h5 class="mb-3"><i class="fas fa-tags"></i> Kategori Anggota</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Kategori (Bisa lebih dari satu)</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kategori_anggota[]" 
                                       value="member" id="kategori_member"
                                       {{ in_array('member', old('kategori_anggota', $dataAnggota['kategori_anggota'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kategori_member">
                                    Member
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kategori_anggota[]" 
                                       value="driver" id="kategori_driver"
                                       {{ in_array('driver', old('kategori_anggota', $dataAnggota['kategori_anggota'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kategori_driver">
                                    Driver
                                </label>
                            </div>
                            @error('kategori_anggota')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- SIM -->
                    <h5 class="mb-3"><i class="fas fa-id-card"></i> Surat Izin Mengemudi (SIM)</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih SIM yang dimiliki (Bisa lebih dari satu)</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sim[]" 
                                       value="A" id="sim_a"
                                       {{ in_array('A', old('sim', $dataAnggota['sim'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sim_a">
                                    SIM A
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sim[]" 
                                       value="B1" id="sim_b1"
                                       {{ in_array('B1', old('sim', $dataAnggota['sim'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sim_b1">
                                    SIM B1
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sim[]" 
                                       value="B2" id="sim_b2"
                                       {{ in_array('B2', old('sim', $dataAnggota['sim'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sim_b2">
                                    SIM B2
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sim[]" 
                                       value="C" id="sim_c"
                                       {{ in_array('C', old('sim', $dataAnggota['sim'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sim_c">
                                    SIM C
                                </label>
                            </div>
                            @error('sim')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="fas fa-save"></i> Simpan Profil
                            </button>
                            <a href="{{ route('member.dashboard') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

