@extends('admin.layouts.app')

@section('title', 'Kelola Link Portal Jurnal')
@section('page-title', 'Manajemen Link Portal & Jurnal OJS')

@section('content')
<div x-data="{ 
    editModalOpen: false,
    editItem: { key: '', title: '', domain: '', link: '', description: '' },
    openEdit(key, item) {
        this.editItem = { key: key, ...item };
        this.editModalOpen = true;
    }
}" class="space-y-8">

    <!-- Journals Header Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main">Gerbang Portal Ekosistem Akademik</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">Kelola nama domain, deskripsi ringkas, serta alamat URL endpoint untuk platform riset and repositori jurnal terakreditasi.</p>
        </div>
    </div>

    <!-- Portals Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($portals as $key => $port)
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between hover:shadow-floating transition-all duration-300 group">
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent">
                        {{ $port['domain'] }}
                    </span>
                    <span class="w-2.5 h-2.5 rounded-full bg-success-green animate-pulse"></span>
                </div>

                <div class="space-y-2">
                    <h4 class="font-primary font-bold text-base text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150">{{ $port['title'] }}</h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed line-clamp-3 dark:text-dark-text-muted">{{ $port['description'] }}</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-border-main/50 dark:border-dark-border flex flex-col space-y-3">
                <div class="p-2.5 rounded-xl bg-bg-alt dark:bg-dark-surface-soft/60 border border-border-main/40 dark:border-dark-border/40 font-body text-[10px] text-text-disabled truncate">
                    <strong>URL:</strong> <span class="font-semibold text-text-sub select-all">{{ $port['link'] }}</span>
                </div>
                
                <!-- Action triggers Alpine modal -->
                <button @click="openEdit('{{ $key }}', {{ json_encode($port) }})" class="w-full py-2.5 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center justify-center space-x-1.5 transition-all duration-150 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    <span>Ubah Konfigurasi Portal</span>
                </button>
            </div>

        </div>
        @endforeach
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

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Ubah Konfigurasi Link Portal</h3>
                
                <form action="{{ route('admin.journal.update') }}" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden Key variable -->
                    <input type="hidden" name="key" x-model="editItem.key">
                    
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Judul Portal / Platform</label>
                        <input type="text" name="title" x-model="editItem.title" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Teks Badge Domain</label>
                            <input type="text" name="domain" x-model="editItem.domain" required placeholder="Contoh: penelitian.mahardika.ac.id" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat URL Endpoint</label>
                            <input type="text" name="link" x-model="editItem.link" required placeholder="https://..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Deskripsi Ringkas Layanan</label>
                        <textarea name="description" x-model="editItem.description" required rows="4" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none"></textarea>
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
