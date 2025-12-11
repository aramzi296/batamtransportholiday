<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Media Management</h1>
        <button type="button" class="btn btn-primary" wire:click="openUploadModal">
            <i class="fas fa-upload"></i> Upload Media
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" 
                           placeholder="Nama file atau keterangan...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe File</label>
                    <select wire:model.live="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="image">Gambar</option>
                        <option value="document">Dokumen</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" wire:click="$set('search', '')" wire:click="$set('type', '')" 
                            class="btn btn-outline-secondary w-100">
                        <i class="fas fa-refresh"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="card">
        <div class="card-body">
            @if($media->count() > 0)
                <div class="row g-3">
                    @foreach($media as $item)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card h-100">
                                <div class="position-relative" style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                    @if($item->is_image)
                                        <img src="{{ $item->thumbnail_url ?? $item->url }}" 
                                             class="card-img-top" 
                                             style="object-fit: cover; width: 100%; height: 100%;"
                                             alt="{{ $item->original_filename }}"
                                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-file fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-truncate" title="{{ $item->original_filename }}">
                                        {{ $item->original_filename }}
                                    </h6>
                                    @if($item->description)
                                        <p class="card-text small text-muted" style="min-height: 40px;">
                                            {{ Str::limit($item->description, 50) }}
                                        </p>
                                    @else
                                        <p class="card-text small text-muted" style="min-height: 40px;">
                                            <em>Tidak ada keterangan</em>
                                        </p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">{{ $item->formatted_size }}</small>
                                        <small class="text-muted">{{ $item->mime_type }}</small>
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary copy-link-btn" 
                                                data-url="{{ $item->url }}"
                                                title="Copy Link">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                wire:click="openViewModal({{ $item->id }})"
                                                title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                wire:click="openEditModal({{ $item->id }})"
                                                title="Edit Keterangan">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteMedia({{ $item->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus file ini? Tindakan ini tidak dapat dibatalkan."
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $media->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada media</h5>
                    <p class="text-muted">Mulai upload file media untuk digunakan di website.</p>
                    <button type="button" class="btn btn-primary" wire:click="openUploadModal">
                        <i class="fas fa-upload"></i> Upload Media Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    @if($showUploadModal)
    <div class="modal fade show" 
         id="uploadModal" 
         tabindex="-1" 
         role="dialog" 
         aria-labelledby="uploadModalLabel" 
         aria-hidden="false"
         style="display: block; z-index: 1055;">
        <div class="modal-dialog" role="document" style="z-index: 1056; position: relative;">
            <div class="modal-content" style="position: relative; z-index: 1057;">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Media</h5>
                    <button type="button" class="btn-close" wire:click="closeUploadModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveFile" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="uploadFile" class="form-label">File *</label>
                            <input type="file" class="form-control" id="uploadFile" wire:model="uploadFile" required>
                            <small class="form-text text-muted">Maksimal 10MB</small>
                            @error('uploadFile') <span class="text-danger">{{ $message }}</span> @enderror
                            @if($uploadFile)
                                <div class="mt-2">
                                    <small class="text-muted">File terpilih: {{ $uploadFile->getClientOriginalName() }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="uploadDescription" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="uploadDescription" wire:model="uploadDescription" rows="3" 
                                      placeholder="Masukkan keterangan untuk file ini (opsional)"></textarea>
                            @error('uploadDescription') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if($uploadFile)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> File: {{ $uploadFile->getClientOriginalName() }}
                                <br><small>Size: {{ number_format($uploadFile->getSize() / 1024, 2) }} KB</small>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeUploadModal" wire:loading.attr="disabled">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveFile">
                            <span wire:loading.remove wire:target="saveFile"><i class="fas fa-upload"></i> Upload</span>
                            <span wire:loading wire:target="saveFile"><i class="fas fa-spinner fa-spin"></i> Uploading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeUploadModal" style="z-index: 1054; pointer-events: auto;"></div>
    </div>
    @endif

    <!-- Edit Description Modal -->
    @if($showEditModal)
    <div class="modal fade show" 
         id="editModal" 
         tabindex="-1" 
         role="dialog" 
         aria-labelledby="editModalLabel" 
         aria-hidden="false"
         style="display: block; z-index: 1055;">
        <div class="modal-dialog" role="document" style="z-index: 1056; position: relative;">
            <div class="modal-content" style="position: relative; z-index: 1057;">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Keterangan</h5>
                    <button type="button" class="btn-close" wire:click="closeEditModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateDescription">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editingDescription" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editingDescription" wire:model="editingDescription" rows="3"></textarea>
                            @error('editingDescription') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeEditModal">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeEditModal" style="z-index: 1054; pointer-events: auto;"></div>
    </div>
    @endif

    <!-- View Media Modal -->
    @if($showViewModal && $viewingMedia)
    <div class="modal fade show" 
         id="viewModal" 
         tabindex="-1" 
         role="dialog" 
         aria-labelledby="viewModalLabel" 
         aria-hidden="false"
         style="display: block; z-index: 1055;">
        <div class="modal-dialog modal-lg" role="document" style="z-index: 1056; position: relative;">
            <div class="modal-content" style="position: relative; z-index: 1057;">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">{{ $viewingMedia->original_filename }}</h5>
                    <button type="button" class="btn-close" wire:click="closeViewModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if($viewingMedia->is_image)
                        <img src="{{ $viewingMedia->url }}" alt="{{ $viewingMedia->original_filename }}" class="img-fluid">
                    @else
                        <div>
                            <i class="fas fa-file fa-5x text-muted mb-3"></i>
                            <p class="text-muted">File ini tidak dapat ditampilkan sebagai preview</p>
                            <a href="{{ $viewingMedia->url }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Buka File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeViewModal" style="z-index: 1054; pointer-events: auto;"></div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Copy to clipboard functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.copy-link-btn')) {
                const btn = e.target.closest('.copy-link-btn');
                const url = btn.getAttribute('data-url');
                
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(() => {
                        // Show success message
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-success');
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-primary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        alert('Gagal menyalin link');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    textArea.style.position = 'fixed';
                    textArea.style.opacity = '0';
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-success');
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-primary');
                        }, 2000);
                    } catch (err) {
                        console.error('Failed to copy:', err);
                        alert('Gagal menyalin link');
                    }
                    document.body.removeChild(textArea);
                }
            }
        });

        // Handle ESC key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open modal
                @this.call('closeUploadModal');
                @this.call('closeEditModal');
                @this.call('closeViewModal');
            }
        });

        // Prevent body scroll when modal is open
        Livewire.hook('morph.updated', ({ el, component }) => {
            const hasOpenModal = document.querySelector('.modal.show');
            if (hasOpenModal) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Listen for successful upload event
        Livewire.on('upload-success', () => {
            setTimeout(() => {
                const uploadModal = document.getElementById('uploadModal');
                if (uploadModal) {
                    uploadModal.style.display = 'none';
                }
                document.body.style.overflow = '';
            }, 500);
        });
    });
</script>
@endpush
