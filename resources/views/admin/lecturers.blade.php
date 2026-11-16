@extends('admin.layouts.app')

@section('title', 'Kelola Direktori Dosen')
@section('page-title', 'Manajemen Direktori Dosen & Peneliti')

@section('content')
<div x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    search: '{{ addslashes($search ?? '') }}',
    editItem: { name: '', slug: '', nidn: '', expertise: '', faculty: '', study_program: '', publications: '0', citations: '0', avatar: '', email: '', scholar_url: '', sinta_url: '', scopus_url: '', research_topics: '' },
    openEdit(item) {
        // Convert array topics to comma separated string for easier edit inputs
        let topicsStr = Array.isArray(item.research_topics) ? item.research_topics.join(', ') : '';
        this.editItem = { ...item, research_topics: topicsStr };
        this.editModalOpen = true;
    },
    matchesSearch(name, nidn, prodi) {
        if (!this.search || !this.search.trim()) return true;
        let q = this.search.toLowerCase().trim();
        return (name || '').toLowerCase().includes(q) ||
               (nidn || '').toLowerCase().includes(q) ||
               (prodi || '').toLowerCase().includes(q);
    }
}" class="space-y-8">

    <!-- Lecturers Header Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main">Direktori Akademik Peneliti</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">Kelola profil akademis dosen, kepakaran bidang keahlian, riwayat publikasi SINTA/Scholar, serta tautan portal riset eksternal.</p>
        </div>
        <button @click="addModalOpen = true" class="px-5 py-3 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center space-x-1.5 transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Tambah Dosen Baru</span>
        </button>
    </div>

    <!-- Search Input & Quick Filter Card -->
    <div class="bg-white border border-border-main p-4 sm:p-5 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-disabled">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                   x-model="search" 
                   placeholder="Cari nama dosen, NIDN, atau prodi..." 
                   class="w-full pl-10 pr-9 py-2.5 text-xs border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/20 transition-all text-text-main placeholder:text-text-disabled">
            <button x-show="search && search.length > 0" 
                    @click="search = ''" 
                    type="button" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-disabled hover:text-text-main transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="flex items-center space-x-2 text-xs font-body text-text-secondary dark:text-dark-text-muted">
            <span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span>
            <span>Pencarian Real-Time (Nama / NIDN / Prodi)</span>
        </div>
    </div>

    <!-- Table List card -->
    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                        <th class="p-4 w-16">Avatar</th>
                        <th class="p-4">Nama Dosen & Gelar</th>
                        <th class="p-4 w-28">NIDN</th>
                        <th class="p-4">Fakultas / Prodi</th>
                        <th class="p-4 w-28 text-center">Publikasi / Sitasi</th>
                        <th class="p-4 w-36 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                    @forelse($lecturers as $lec)
                    <tr x-show="matchesSearch('{{ addslashes($lec['name'] ?? '') }}', '{{ addslashes($lec['nidn'] ?? '') }}', '{{ addslashes($lec['study_program'] ?? ($lec['prodi'] ?? '')) }}')" 
                        class="hover:bg-mahardika-navy-pale/30 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                        <td class="p-4">
                            <div class="w-10 h-10 rounded-full overflow-hidden border border-border-main/50 dark:border-dark-border">
                                <img src="{{ !empty($lec['avatar']) && !\Illuminate\Support\Str::startsWith($lec['avatar'], ['http://', 'https://', '/']) ? asset('storage/' . $lec['avatar']) : ($lec['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256') }}" alt="{{ $lec['name'] }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col space-y-1">
                                <a href="{{ route('landing.lecturer', ['slug' => $lec['slug']]) }}" target="_blank" class="font-bold text-text-main leading-normal hover:text-mahardika-navy dark:hover:text-navy-accent text-sm">{{ $lec['name'] }}</a>
                                <span class="text-[10px] text-mahardika-navy dark:text-navy-accent font-semibold">{{ $lec['expertise'] }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-text-main">{{ $lec['nidn'] }}</td>
                        <td class="p-4">
                            <div class="flex flex-col space-y-0.5">
                                <span class="font-semibold text-text-main">{{ $lec['faculty'] }}</span>
                                <span class="text-[10px] text-text-disabled">{{ $lec['study_program'] }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-center font-bold text-text-main">
                            <span class="text-mahardika-navy dark:text-navy-accent">{{ $lec['publications'] }}</span>
                            <span class="text-text-disabled mx-1">/</span>
                            <span class="text-gold-accent">{{ $lec['citations'] }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit button triggers Alpine -->
                                <button @click="openEdit({{ json_encode($lec) }})" class="p-2 rounded-lg border border-border-main hover:bg-mahardika-navy-pale text-mahardika-navy dark:border-dark-border dark:text-white dark:hover:bg-dark-surface-soft transition-colors duration-150" title="Edit Profil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                
                                <!-- Delete form -->
                                <form action="{{ route('admin.lecturer.delete', ['slug' => $lec['slug']]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data profil dosen ini dari direktori?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg border border-alert-red/35 hover:bg-alert-red-pale text-alert-red transition-colors duration-150" title="Hapus Profil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-text-disabled">Belum ada dosen tercatat.</td>
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
                 class="relative transform overflow-hidden rounded-3xl bg-white border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-xl dark:bg-dark-surface dark:border-dark-border">
                
                <button @click="addModalOpen = false" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Tambah Profil Dosen Baru</h3>
                
                <form action="{{ route('admin.lecturer.create') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body text-xs">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Lengkap & Gelar</label>
                            <input type="text" name="name" required placeholder="Dr. Hermawan, M.Kom." class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">NIDN Nasional</label>
                            <input type="text" name="nidn" required placeholder="0420088901" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Fakultas Utama</label>
                            <select name="faculty" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Fakultas Ilmu Kesehatan">Fakultas Ilmu Kesehatan</option>
                                <option value="Fakultas Ilmu Komputer">Fakultas Ilmu Komputer</option>
                                <option value="Fakultas Farmasi">Fakultas Farmasi</option>
                                <option value="Fakultas Teknik">Fakultas Teknik</option>
                                <option value="Fakultas Keguruan & Ilmu Pendidikan">Fakultas Keguruan & Ilmu Pendidikan</option>
                                <option value="Fakultas Ekonomi & Bisnis">Fakultas Ekonomi & Bisnis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Program Studi</label>
                            <select name="study_program" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="" disabled selected>Pilih Program Studi</option>
                                <option value="Kebidanan">Kebidanan</option>
                                <option value="Keperawatan">Keperawatan</option>
                                <option value="Rekam Medis">Rekam Medis</option>
                                <option value="Kesmas">Kesmas</option>
                                <option value="Informatika">Informatika</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Fokus Kepakaran Utama</label>
                            <input type="text" name="expertise" required placeholder="Kecerdasan Buatan & IoT" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat Email Akademis</label>
                            <input type="email" name="email" required placeholder="hermawan@mahardika.ac.id" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Publikasi</label>
                            <input type="number" name="publications" placeholder="10" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Sitasi</label>
                            <input type="number" name="citations" placeholder="85" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Upload Foto Avatar</label>
                            <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-mahardika-navy-pale file:text-mahardika-navy hover:file:bg-mahardika-navy hover:file:text-white transition-all cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Scholar URL / ID</label>
                            <input type="text" name="scholar_url" placeholder="Contoh: P4tORs8AAAAJ atau URL penuh" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">SINTA URL / ID</label>
                            <input type="text" name="sinta_url" placeholder="Contoh: 6733909 atau URL penuh" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Topik Penelitian Terkait (Pisahkan dengan koma)</label>
                        <input type="text" name="research_topics" placeholder="Machine Learning, Big Data, Smart Farming" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
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
                 class="relative transform overflow-hidden rounded-3xl bg-white border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-xl dark:bg-dark-surface dark:border-dark-border">
                
                <button @click="editModalOpen = false" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-primary font-extrabold text-lg text-text-main mb-6">Ubah Profil Dosen</h3>
                
                <!-- Dynamic form action wired to the current item slug -->
                <form :action="'{{ url('admin/lecturers') }}/' + editItem.slug" method="POST" enctype="multipart/form-data" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Lengkap & Gelar</label>
                            <input type="text" name="name" x-model="editItem.name" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">NIDN Nasional</label>
                            <input type="text" name="nidn" x-model="editItem.nidn" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Fakultas Utama</label>
                            <select name="faculty" x-model="editItem.faculty" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Fakultas Ilmu Kesehatan">Fakultas Ilmu Kesehatan</option>
                                <option value="Fakultas Ilmu Komputer">Fakultas Ilmu Komputer</option>
                                <option value="Fakultas Farmasi">Fakultas Farmasi</option>
                                <option value="Fakultas Teknik">Fakultas Teknik</option>
                                <option value="Fakultas Keguruan & Ilmu Pendidikan">Fakultas Keguruan & Ilmu Pendidikan</option>
                                <option value="Fakultas Ekonomi & Bisnis">Fakultas Ekonomi & Bisnis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Program Studi</label>
                            <select name="study_program" x-model="editItem.study_program" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                                <option value="Kebidanan">Kebidanan</option>
                                <option value="Keperawatan">Keperawatan</option>
                                <option value="Rekam Medis">Rekam Medis</option>
                                <option value="Kesmas">Kesmas</option>
                                <option value="Informatika">Informatika</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Fokus Kepakaran Utama</label>
                            <input type="text" name="expertise" x-model="editItem.expertise" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat Email Akademis</label>
                            <input type="email" name="email" x-model="editItem.email" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Publikasi</label>
                            <input type="number" name="publications" x-model="editItem.publications" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Sitasi</label>
                            <input type="number" name="citations" x-model="editItem.citations" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Ganti Foto Avatar</label>
                            <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-mahardika-navy-pale file:text-mahardika-navy hover:file:bg-mahardika-navy hover:file:text-white transition-all cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Scholar URL / ID</label>
                            <input type="text" name="scholar_url" x-model="editItem.scholar_url" placeholder="Contoh: P4tORs8AAAAJ atau URL penuh" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">SINTA URL / ID</label>
                            <input type="text" name="sinta_url" x-model="editItem.sinta_url" placeholder="Contoh: 6733909 atau URL penuh" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Topik Penelitian Terkait (Pisahkan dengan koma)</label>
                        <input type="text" name="research_topics" x-model="editItem.research_topics" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
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
