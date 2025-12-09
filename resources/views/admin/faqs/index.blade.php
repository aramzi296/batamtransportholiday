@extends('layouts.admin')

@section('title', 'FAQ')
@section('page-title', 'FAQ Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Daftar FAQ</h4>
        <p class="text-muted">Kelola Frequently Asked Questions</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah FAQ
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pertanyaan</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td><code>#{{ $faq->id }}</code></td>
                        <td>
                            <strong>{{ $faq->question }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $faq->sort_order }}</span>
                        </td>
                        <td>
                            @if($faq->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus FAQ ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada FAQ</p>
                            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah FAQ Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

