@extends('admin.layouts.app')

@section('title', 'Manajemen E-Catalog Penelitian')
@section('page-title', 'Katalog Penelitian (BIMA Kemdiktisaintek)')

@section('content')
<div x-data="{ createModal: false, editModal: false, editItem: {} }" class="space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-900">×</button>
    </div>
    @endif

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-dark-surface p-6 rounded-2xl border border-border-main dark:border-dark-border shadow-card">
        <div>
            <h3 class="font-primary font-bold text-base text-text-main dark:text-white">Daftar Penelitian Hibah Akademis</h3>
            <p class="text-xs text-text-secondary dark:text-dark-text-muted mt-0.5">Kelola seluruh data riset, skema hibah BIMA, bidang fokus, dan laporan penelitian.</p>
        </div>
        
        <button @click="createModal = true" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-mahardika-navy hover:bg-blue-900 text-white rounded-xl text-xs font-bold transition-all shadow-md shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Penelitian Baru</span>
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-dark-surface rounded-2xl border border-border-main dark:border-dark-border shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt dark:bg-dark-surface-soft border-b border-border-main dark:border-dark-border text-[11px] font-bold text-text-secondary dark:text-dark-text-muted uppercase tracking-wider">
                        <th class="py-4 px-6">Judul Riset</th>
                        <th class="py-4 px-6">Ketua Dosen</th>
                        <th class="py-4 px-6">Skema & Bidang</th>
                        <th class="py-4 px-6">Tahun & Dana</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main dark:divide-dark-border text-xs text-text-main dark:text-dark-text-muted">
                    @forelse($penelitians as $item)
                        <tr class="hover:bg-bg-alt/50 dark:hover:bg-dark-surface-soft/40 transition-colors">
                            <td class="py-4 px-6 font-semibold max-w-xs">
                                <div class="line-clamp-2 text-text-main dark:text-white font-bold">{{ $item->judul }}</div>
                                @if($item->file_pdf_url)
                                    <a href="{{ $item->file_pdf_url }}" target="_blank" class="text-[10px] text-mahardika-navy dark:text-blue-400 hover:underline inline-flex items-center space-x-1 mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                                        <span>PDF Dokumen</span>
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-text-main dark:text-white">{{ $item->ketuaDosen->nama ?? '-' }}</div>
                                <div class="text-[10px] text-text-secondary dark:text-dark-text-muted">NIDN: {{ $item->ketuaDosen->nidn ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-900/30 text-mahardika-navy dark:text-blue-300">
                                    {{ $item->skema }}
                                </span>
                                <div class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">{{ $item->bidang_fokus }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-text-main dark:text-white">{{ $item->tahun_pelaksanaan }}</div>
                                <div class="text-[10px] text-mahardika-green font-semibold">
                                    {{ $item->dana_disetujui ? 'Rp ' . number_format($item->dana_disetujui, 0, ',', '.') : '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button @click="editItem = {{ json_encode($item) }}; editModal = true" class="px-3 py-1.5 bg-gray-100 dark:bg-dark-surface-soft hover:bg-gray-200 text-text-main dark:text-white rounded-lg text-[11px] font-bold transition-all">
                                    Edit
                                </button>
                                <form action="{{ route('admin.penelitian.delete', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data penelitian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 text-alert-red rounded-lg text-[11px] font-bold transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-text-secondary dark:text-dark-text-muted">Belum ada data penelitian terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-dark-surface rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-floating max-h-[90vh] overflow-y-auto border border-border-main dark:border-dark-border">
            <div class="flex items-center justify-between border-b border-border-main dark:border-dark-border pb-3">
                <h3 class="font-primary font-bold text-base text-text-main dark:text-white">Tambah Data Penelitian Baru</h3>
                <button @click="createModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('admin.penelitian.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold mb-1">Judul Penelitian *</label>
                    <textarea name="judul" rows="2" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Ketua Dosen *</label>
                        <select name="ketua_dosen_id" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                            <option value="">Pilih Dosen...</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->prodi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Tahun Pelaksanaan *</label>
                        <input type="number" name="tahun_pelaksanaan" value="{{ date('Y') }}" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Skema Hibah BIMA *</label>
                        <input type="text" name="skema" placeholder="Contoh: Penelitian Terapan (PT)" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Bidang Fokus *</label>
                        <input type="text" name="bidang_fokus" placeholder="Contoh: Kesehatan & Obat" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Dana Disetujui (Rp)</label>
                        <input type="number" name="dana_disetujui" placeholder="100000000" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Status Riset *</label>
                        <select name="status" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                            <option value="Didanai">Didanai</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Diusulkan">Diusulkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Abstrak / Ringkasan</label>
                    <textarea name="abstrak" rows="4" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Link URL PDF Dokumen</label>
                    <input type="url" name="file_pdf_url" placeholder="https://..." class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t">
                    <button type="button" @click="createModal = false" class="px-4 py-2 bg-gray-100 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-mahardika-navy text-white text-xs font-bold rounded-xl">Simpan Penelitian</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-dark-surface rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-floating max-h-[90vh] overflow-y-auto border border-border-main dark:border-dark-border">
            <div class="flex items-center justify-between border-b border-border-main dark:border-dark-border pb-3">
                <h3 class="font-primary font-bold text-base text-text-main dark:text-white">Edit Data Penelitian</h3>
                <button @click="editModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form :action="'{{ url('admin/penelitian') }}/' + editItem.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold mb-1">Judul Penelitian *</label>
                    <textarea name="judul" x-model="editItem.judul" rows="2" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Ketua Dosen *</label>
                        <select name="ketua_dosen_id" x-model="editItem.ketua_dosen_id" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                            <option value="">Pilih Dosen...</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->prodi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Tahun Pelaksanaan *</label>
                        <input type="number" name="tahun_pelaksanaan" x-model="editItem.tahun_pelaksanaan" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Skema Hibah BIMA *</label>
                        <input type="text" name="skema" x-model="editItem.skema" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Bidang Fokus *</label>
                        <input type="text" name="bidang_fokus" x-model="editItem.bidang_fokus" required class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-1">Dana Disetujui (Rp)</label>
                        <input type="number" name="dana_disetujui" x-model="editItem.dana_disetujui" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1">Status Riset *</label>
                        <select name="status" x-model="editItem.status" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                            <option value="Didanai">Didanai</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Diusulkan">Diusulkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Abstrak / Ringkasan</label>
                    <textarea name="abstrak" x-model="editItem.abstrak" rows="4" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Link URL PDF Dokumen</label>
                    <input type="url" name="file_pdf_url" x-model="editItem.file_pdf_url" class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-gray-100 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-mahardika-navy text-white text-xs font-bold rounded-xl">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
