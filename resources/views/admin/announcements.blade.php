@extends('admin.layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Manajemen Pengumuman & Agenda')

@section('content')
<div x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    editItem: { id: '', title: '', category: '', category_type: '', summary: '', file_size: '', link: '', show_popup: false, start_date: '', end_date: '' },
    openEdit(item) {
        this.editItem = { ...item };
        this.editModalOpen = true;
    }
}" class="space-y-8">

    <!-- Announcements Header Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main">Papan Informasi Akademis</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">Kelola seluruh rilis artikel, agenda, pengumuman hibah internal/nasional, serta pop-up dialog landing page.</p>
        </div>
        <button @click="addModalOpen = true" class="px-5 py-3 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center space-x-1.5 transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Tambah Rilis Baru</span>
        </button>
    </div>

    <!-- Table List card -->
    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                        <th class="p-4 w-12 text-center">ID</th>
                        <th class="p-4">Judul Pengumuman</th>
                        <th class="p-4 w-28">Kategori</th>
                        <th class="p-4 w-32">Tanggal Rilis</th>
                        <th class="p-4 w-28 text-center">Pop-up</th>
                        <th class="p-4 w-36 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                    @forelse($announcements as $ann)
                    <tr class="hover:bg-mahardika-navy-pale/30 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                        <td class="p-4 font-bold text-text-main text-center">{{ $ann['id'] }}</td>
                        <td class="p-4">
                            <div class="flex flex-col space-y-1">
                                <span class="font-bold text-text-main leading-normal text-sm">{{ $ann['title'] }}</span>
                                <span class="text-[10px] text-text-disabled line-clamp-1">{{ $ann['summary'] }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                @if($ann['category_type'] == 'important') bg-alert-red-pale text-alert-red
                                @elseif($ann['category_type'] == 'hibah') bg-gold-accent/15 text-gold-accent
                                @elseif($ann['category_type'] == 'baru') bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent
                                @else bg-success-green-pale text-success-green @endif">
                                {{ $ann['category'] }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-text-main">{{ $ann['date'] }}</td>
                        <td class="p-4 text-center">
                            @if(isset($ann['show_popup']) && $ann['show_popup'] === true)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-success-green text-white">
                                <span class="w-1.5 h-1.5 rounded-full bg-white mr-1 animate-pulse"></span> Aktif
                            </span>
                            @else
                            <span class="text-[9px] text-text-disabled font-semibold">Mati</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit button triggers Alpine -->
                                <button @click="openEdit({{ json_encode($ann) }})" class="p-2 rounded-lg border border-border-main hover:bg-mahardika-navy-pale text-mahardika-navy dark:border-dark-border dark:text-white dark:hover:bg-dark-surface-soft transition-colors duration-150" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                
                                <!-- Delete form -->
                                <form action="{{ route('admin.announcement.delete', ['id' => $ann['id']]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg border border-alert-red/35 hover:bg-alert-red-pale text-alert-red transition-colors duration-150" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-text-disabled">Belum ada pengumuman tercatat.</td>
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

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Tambah Pengumuman Baru</h3>
                
                <form action="{{ route('admin.announcement.create') }}" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Judul Pengumuman</label>
                        <input type="text" name="title" required placeholder="Contoh: Penerimaan Proposal Penelitian Skema Madya..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/20">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Kategori</label>
                            <input type="text" name="category" required placeholder="Contoh: WORKSHOP" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jenis Kategori</label>
                            <select name="category_type" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="umum">UMUM (Hijau)</option>
                                <option value="important">PENTING (Merah)</option>
                                <option value="hibah">JURNAL (Emas)</option>
                                <option value="baru">WORKSHOP (Navy)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Isi Ringkasan Pengumuman</label>
                        <textarea name="summary" required rows="4" placeholder="Tuliskan isi deskripsi pengumuman secara singkat di sini..." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Ukuran Lampiran File</label>
                            <input type="text" name="file_size" placeholder="1.2 MB" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tautan Dokumen External</label>
                            <input type="text" name="link" placeholder="#" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tanggal Mulai Popup</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tanggal Berakhir Popup</label>
                            <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3.5 bg-bg-alt rounded-2xl dark:bg-dark-surface-soft/40 border border-border-main/50 dark:border-dark-border/40">
                        <input type="checkbox" name="show_popup" id="show_popup" value="1" class="w-4 h-4 rounded text-mahardika-navy border-border-main focus:ring-mahardika-navy">
                        <label for="show_popup" class="font-bold text-text-main dark:text-white cursor-pointer select-none">Tampilkan Sebagai Pop-Up Dialog Landing Page</label>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-3 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150">Batal</button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-bold transition-all duration-150 shadow-subtle">Simpan & Publikasikan</button>
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

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Ubah Rilis Pengumuman</h3>
                
                <!-- Action target dynamic wired via JS -->
                <form :action="'{{ url('admin/announcements') }}/' + editItem.id" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Judul Pengumuman</label>
                        <input type="text" name="title" x-model="editItem.title" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/20">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Kategori</label>
                            <input type="text" name="category" x-model="editItem.category" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jenis Kategori</label>
                            <select name="category_type" x-model="editItem.category_type" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="umum">UMUM (Hijau)</option>
                                <option value="important">PENTING (Merah)</option>
                                <option value="hibah">JURNAL (Emas)</option>
                                <option value="baru">WORKSHOP (Navy)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Isi Ringkasan Pengumuman</label>
                        <textarea name="summary" x-model="editItem.summary" required rows="4" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Ukuran Lampiran File</label>
                            <input type="text" name="file_size" x-model="editItem.file_size" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tautan Dokumen External</label>
                            <input type="text" name="link" x-model="editItem.link" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tanggal Mulai Popup</label>
                            <input type="date" name="start_date" x-model="editItem.start_date" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Tanggal Berakhir Popup</label>
                            <input type="date" name="end_date" x-model="editItem.end_date" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3.5 bg-bg-alt rounded-2xl dark:bg-dark-surface-soft/40 border border-border-main/50 dark:border-dark-border/40">
                        <input type="checkbox" name="show_popup" id="edit_show_popup" x-model="editItem.show_popup" class="w-4 h-4 rounded text-mahardika-navy border-border-main focus:ring-mahardika-navy">
                        <label for="edit_show_popup" class="font-bold text-text-main dark:text-white cursor-pointer select-none">Tampilkan Sebagai Pop-Up Dialog Landing Page</label>
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
