@extends('admin.layouts.app')

@section('title', 'Kelola SOP & Dokumen')
@section('page-title', 'Manajemen Peraturan, SOP, & Panduan')

@section('content')
<div x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    editItem: { id: '', title: '', category: '', type: '', size: '1.2 MB', link: '#', status: 'Published' },
    openEdit(item) {
        this.editItem = { ...item };
        this.editModalOpen = true;
    }
}" class="space-y-8">

    <!-- Documents Header Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main">Pusat Unduhan Dokumen Resmi</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">Kelola berkas SOP, peraturan rektorat, panduan penulisan proposal hibah internal/nasional, and templat formulir pelaporan luaran.</p>
        </div>
        <button @click="addModalOpen = true" class="px-5 py-3 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center space-x-1.5 transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Unggah Dokumen</span>
        </button>
    </div>

    <!-- Table List card -->
    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                        <th class="p-4 w-12 text-center">ID</th>
                        <th class="p-4">Nama Dokumen / Berkas Resmi</th>
                        <th class="p-4 w-28">Kategori</th>
                        <th class="p-4 w-28">Tipe</th>
                        <th class="p-4 w-24">Ukuran</th>
                        <th class="p-4 w-28 text-center">Status</th>
                        <th class="p-4 w-36 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-mahardika-navy-pale/30 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                        <td class="p-4 font-bold text-text-main text-center">{{ $doc['id'] }}</td>
                        <td class="p-4">
                            <div class="flex flex-col space-y-1">
                                <a href="{{ $doc['link'] }}" target="_blank" class="font-bold text-text-main leading-normal hover:text-mahardika-navy dark:hover:text-navy-accent text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-text-disabled shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" /></svg>
                                    <span>{{ $doc['title'] }}</span>
                                </a>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent">
                                {{ $doc['category'] }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-success-green-pale text-success-green">
                                {{ $doc['type'] }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-text-secondary dark:text-dark-text-muted">{{ $doc['size'] }}</td>
                        <td class="p-4 text-center">
                            @if(isset($doc['status']) && $doc['status'] == 'Published')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-success-green text-white">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-text-disabled text-white">
                                Arsip
                            </span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit button triggers Alpine -->
                                <button @click="openEdit({{ json_encode($doc) }})" class="p-2 rounded-lg border border-border-main hover:bg-mahardika-navy-pale text-mahardika-navy dark:border-dark-border dark:text-white dark:hover:bg-dark-surface-soft transition-colors duration-150" title="Edit Dokumen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                
                                <!-- Delete form -->
                                <form action="{{ route('admin.document.delete', ['id' => $doc['id']]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas dokumen resmi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg border border-alert-red/35 hover:bg-alert-red-pale text-alert-red transition-colors duration-150" title="Hapus Dokumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-text-disabled">Belum ada dokumen tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CREATE MODAL (Alpine.js) -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        
        <!-- Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="addModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-lg dark:bg-dark-surface dark:border-dark-border">
                
                <button @click="addModalOpen = false" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Unggah Dokumen Baru</h3>
                
                <form action="{{ route('admin.document.create') }}" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Judul Dokumen Resmi</label>
                        <input type="text" name="title" required placeholder="Contoh: Panduan Hibah Internal Penelitian Tahun 2026..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Kategori Format</label>
                            <select name="category" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="PERATURAN">PERATURAN</option>
                                <option value="SOP">SOP</option>
                                <option value="PANDUAN">PANDUAN</option>
                                <option value="TEMPLAT">TEMPLAT FORMULIR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tipe Bidang Layanan</label>
                            <select name="type" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Penelitian">Penelitian (Riset)</option>
                                <option value="Pengabdian">Pengabdian (PkM)</option>
                                <option value="Umum">Umum (Institusional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Ukuran Berkas Dokumen</label>
                            <input type="text" name="size" placeholder="1.2 MB" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Status Publikasi</label>
                            <select name="status" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Published">Aktif (Published)</option>
                                <option value="Archived">Arsip (Draft)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tautan Unduhan (URL)</label>
                        <input type="text" name="link" placeholder="https://drive.google.com/..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-3 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150">Batal</button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-bold transition-all duration-150 shadow-subtle">Unggah Dokumen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL (Alpine.js) -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
        
        <!-- Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="editModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-lg dark:bg-dark-surface dark:border-dark-border">
                
                <button @click="editModalOpen = false" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Ubah Berkas Dokumen</h3>
                
                <!-- Dynamic form action wired to the current item id -->
                <form :action="'{{ url('admin/documents') }}/' + editItem.id" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Judul Dokumen Resmi</label>
                        <input type="text" name="title" x-model="editItem.title" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Kategori Format</label>
                            <select name="category" x-model="editItem.category" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="PERATURAN">PERATURAN</option>
                                <option value="SOP">SOP</option>
                                <option value="PANDUAN">PANDUAN</option>
                                <option value="TEMPLAT">TEMPLAT FORMULIR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tipe Bidang Layanan</label>
                            <select name="type" x-model="editItem.type" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Penelitian">Penelitian (Riset)</option>
                                <option value="Pengabdian">Pengabdian (PkM)</option>
                                <option value="Umum">Umum (Institusional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Ukuran Berkas Dokumen</label>
                            <input type="text" name="size" x-model="editItem.size" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Status Publikasi</label>
                            <select name="status" x-model="editItem.status" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Published">Aktif (Published)</option>
                                <option value="Archived">Arsip (Draft)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tautan Unduhan (URL)</label>
                        <input type="text" name="link" x-model="editItem.link" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="editModalOpen = false" class="px-5 py-3 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150">Batal</button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-bold transition-all duration-150 shadow-subtle">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
