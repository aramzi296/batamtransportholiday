@extends('layouts.member')

@section('title', 'Daftar Member')
@section('page-title', 'Daftar Member')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Daftar Semua Member</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('member.members.index') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="form-label">Cari Member</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Nama, email, atau nomor WhatsApp..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if($members->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto Profil</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomor WhatsApp</th>
                                    <th>Kategori Anggota</th>
                                    <th>Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $index => $member)
                                    @php
                                        $dataAnggota = $member->data_anggota ?? [];
                                        $kategoriAnggota = $dataAnggota['kategori_anggota'] ?? null;
                                        // Handle jika kategori_anggota adalah array
                                        if (is_array($kategoriAnggota)) {
                                            $kategoriAnggota = !empty($kategoriAnggota) ? implode(', ', $kategoriAnggota) : null;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $member->foto_profil_url }}" alt="{{ $member->name }}" 
                                                 class="img-thumbnail rounded-circle" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=50&background=17a2b8&color=ffffff&bold=true';">
                                        </td>
                                        <td>
                                            <strong>{{ $member->name }}</strong>
                                        </td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            @if($member->nomor_whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->nomor_whatsapp) }}" 
                                                   target="_blank" 
                                                   class="text-success">
                                                    <i class="fab fa-whatsapp"></i> {{ $member->nomor_whatsapp }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($kategoriAnggota && is_string($kategoriAnggota))
                                                <span class="badge bg-primary">{{ $kategoriAnggota }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($member->created_at)->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $members->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Tidak ada member yang ditemukan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

