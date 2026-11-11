@extends('admin.layouts.app')

@section('title', 'Tambah Profil Dosen Baru')
@section('page-title', 'Tambah Profil Dosen Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold font-primary text-text-main dark:text-dark-text-main">Tambah Profil Dosen & Peneliti Baru</h2>
            <p class="text-xs text-text-secondary dark:text-dark-text-muted mt-1">Formulir pendaftaran profil dosen sekaligus pembuatan akun login dosen otomatis.</p>
        </div>
        <a href="{{ route('admin.lecturers') }}" class="px-4 py-2 text-xs font-semibold rounded-xl border border-border-main text-text-secondary hover:bg-mahardika-navy-pale/50 dark:border-dark-border dark:text-dark-text-muted transition-colors">
            Kembali ke Direktori
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium dark:bg-emerald-950/40 dark:border-emerald-800/60 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs dark:bg-rose-950/40 dark:border-rose-800/60 dark:text-rose-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-border-main rounded-3xl p-6 sm:p-8 shadow-card dark:bg-dark-surface dark:border-dark-border">
        <form action="{{ route('admin.lecturer.create') }}" method="POST" enctype="multipart/form-data" class="space-y-5 font-body text-xs">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Nama Lengkap & Gelar *</label>
                    <input type="text" name="name" required placeholder="Contoh: Dr. Hermawan, M.Kom." value="{{ old('name') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">NIDN Nasional (Default Password) *</label>
                    <input type="text" name="nidn" required placeholder="Contoh: 0420088901" value="{{ old('nidn') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                    <span class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-1 block">NIDN ini otomatis digunakan sebagai password login akun dosen.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Fakultas Utama *</label>
                    <select name="faculty" required class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                        <option value="Fakultas Ilmu Kesehatan">Fakultas Ilmu Kesehatan</option>
                        <option value="Fakultas Ilmu Komputer">Fakultas Ilmu Komputer</option>
                        <option value="Fakultas Farmasi">Fakultas Farmasi</option>
                        <option value="Fakultas Teknik">Fakultas Teknik</option>
                        <option value="Fakultas Keguruan & Ilmu Pendidikan">Fakultas Keguruan & Ilmu Pendidikan</option>
                        <option value="Fakultas Ekonomi & Bisnis">Fakultas Ekonomi & Bisnis</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Program Studi *</label>
                    <input type="text" name="study_program" required placeholder="Contoh: S1 Teknik Informatika" value="{{ old('study_program') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Fokus Kepakaran Utama *</label>
                    <input type="text" name="expertise" required placeholder="Contoh: Kecerdasan Buatan & IoT" value="{{ old('expertise') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat Email Akademis *</label>
                    <input type="email" name="email" required placeholder="Contoh: hermawan@mahardika.ac.id" value="{{ old('email') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Publikasi</label>
                    <input type="number" name="publications" placeholder="10" value="{{ old('publications', 0) }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Jumlah Sitasi</label>
                    <input type="number" name="citations" placeholder="85" value="{{ old('citations', 0) }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Upload Foto Avatar</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-mahardika-navy-pale file:text-mahardika-navy hover:file:bg-mahardika-navy hover:file:text-white transition-all cursor-pointer">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Scholar URL</label>
                    <input type="text" name="scholar_url" placeholder="https://scholar.google.com/..." value="{{ old('scholar_url', '#') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">SINTA URL</label>
                    <input type="text" name="sinta_url" placeholder="https://sinta.kemdikbud.go.id/..." value="{{ old('sinta_url', '#') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Scopus URL</label>
                    <input type="text" name="scopus_url" placeholder="https://www.scopus.com/..." value="{{ old('scopus_url', '#') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase text-[10px] text-text-secondary dark:text-dark-text-muted mb-2">Topik Riset Utama (Pisahkan dengan koma)</label>
                <input type="text" name="research_topics" placeholder="Machine Learning, Data Mining, Sistem Tertanam" value="{{ old('research_topics') }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-border-main dark:border-dark-border">
                <a href="{{ route('admin.lecturers') }}" class="px-5 py-2.5 rounded-xl font-bold text-xs text-text-secondary hover:bg-mahardika-navy-pale/50 dark:text-dark-text-muted transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle dark:bg-navy-accent transition-all duration-200">
                    Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
