@extends('admin.layouts.app')

@section('title', 'Kelola Link Portal Jurnal')
@section('page-title', 'Manajemen Link Portal & Jurnal OJS')

@section('content')
<div x-data="{ 
    createModalOpen: false,
    editModalOpen: false,
    editItem: { id: '', nama_portal: '', domain: '', url: '', deskripsi: '' },
    openEdit(item) {
        this.editItem = { 
            id: item.id || '',
            nama_portal: item.nama_portal || item.title || '',
            domain: item.domain || '',
            url: item.url || item.link || '',
            deskripsi: item.deskripsi || item.description || ''
        };
        this.editModalOpen = true;
    }
}" class="space-y-8">

    <!-- Notifikasi Sukses / Pesan Flash -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between shadow-subtle transition-all duration-200">
        <div class="flex items-center space-x-2.5">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75 font-bold ml-4">✕</button>
    </div>
    @endif

    <!-- Journals Header Card & Tambah Tombol -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-3">
            <div>
                <h3 class="font-primary font-bold text-base sm:text-lg text-text-main">Gerbang Portal Ekosistem Akademik</h3>
                <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1">Kelola nama domain, deskripsi ringkas, serta alamat URL endpoint untuk platform riset and repositori jurnal terakreditasi.</p>
            </div>
            
            <!-- Tombol Tambah Portal Baru di Bawah Area Judul -->
            <div>
                <button @click="createModalOpen = true" 
                        type="button"
                        class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-primary font-bold text-xs shadow-subtle hover:scale-[1.02] active:scale-[0.98] transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Portal/Jurnal Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Dynamic Portals Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($journals as $journal)
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between hover:shadow-floating transition-all duration-300 group">
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent truncate max-w-[200px]">
                        {{ $journal->domain ?? parse_url($journal->url, PHP_URL_HOST) }}
                    </span>
                    <span class="w-2.5 h-2.5 rounded-full bg-success-green animate-pulse shrink-0"></span>
                </div>

                <div class="space-y-2">
                    <h4 class="font-primary font-bold text-base text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150">
                        {{ $journal->nama_portal ?? $journal->title }}
                    </h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed line-clamp-3 dark:text-dark-text-muted">
                        {{ $journal->deskripsi ?? $journal->description }}
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-border-main/50 dark:border-dark-border flex flex-col space-y-3">
                <div class="p-2.5 rounded-xl bg-bg-alt dark:bg-dark-surface-soft/60 border border-border-main/40 dark:border-dark-border/40 font-body text-[10px] text-text-disabled truncate">
                    <strong>URL:</strong> <span class="font-semibold text-text-sub select-all">{{ $journal->url ?? $journal->link }}</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <!-- Action triggers Alpine edit modal -->
                    <button @click="openEdit({{ json_encode($journal) }})" 
                            type="button"
                            class="flex-1 py-2.5 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center justify-center space-x-1.5 transition-all duration-150 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        <span>Ubah Konfigurasi Portal</span>
                    </button>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.journal.destroy', $journal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus portal/jurnal ini?')" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2.5 rounded-xl border border-red-200 dark:border-red-900 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors"
                                title="Hapus Portal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full py-12 px-6 text-center bg-white border border-border-main rounded-3xl dark:bg-dark-surface dark:border-dark-border">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <h4 class="font-primary font-bold text-base text-text-main">Belum Ada Portal / Jurnal</h4>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1 max-w-sm mx-auto">Klik tombol "+ Tambah Portal/Jurnal Baru" di atas untuk mendaftarkan portal OJS atau tautan akademik pertama.</p>
        </div>
        @endforelse
    </div>

    <!-- 1. MODAL TAMBAH PORTAL BARU (Alpine.js) -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="createModalOpen = false"></div>
        
        <!-- Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="createModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-lg dark:bg-dark-surface dark:border-dark-border">
                
                <button @click="createModalOpen = false" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Tambah Portal / Jurnal Baru</h3>
                
                <form action="{{ route('admin.journal.store') }}" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Judul Portal / Platform <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_portal" required placeholder="Contoh: Jurnal Inovasi Kesehatan" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Teks Badge Domain</label>
                            <input type="text" name="domain" placeholder="Contoh: jurnal.mahardika.ac.id" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat URL Endpoint <span class="text-red-500">*</span></label>
                            <input type="url" name="url" required placeholder="https://jurnal.mahardika.ac.id/..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Deskripsi Ringkas Layanan <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" required rows="4" placeholder="Jelaskan cakupan bidang riset atau layanan platform ini..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25"></textarea>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="createModalOpen = false" class="px-5 py-3 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150">Batal</button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-bold transition-all duration-150 shadow-subtle">Simpan Portal Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL UBAH KONFIGURASI PORTAL (Alpine.js) -->
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

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Ubah Konfigurasi Link Portal</h3>
                
                <form :action="'{{ url('/admin/journals') }}/' + editItem.id" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden ID variable -->
                    <input type="hidden" name="id" :value="editItem.id">
                    
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Judul Portal / Platform <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_portal" x-model="editItem.nama_portal" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Teks Badge Domain</label>
                            <input type="text" name="domain" x-model="editItem.domain" placeholder="Contoh: penelitian.mahardika.ac.id" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat URL Endpoint <span class="text-red-500">*</span></label>
                            <input type="url" name="url" x-model="editItem.url" required placeholder="https://..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Deskripsi Ringkas Layanan <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" x-model="editItem.deskripsi" required rows="4" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/25"></textarea>
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
