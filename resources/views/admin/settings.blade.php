@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')
@section('page-title', 'Konfigurasi Variabel LPPM')

@section('content')
<div class="space-y-8" x-data="{ tab: 'hero' }">

    <!-- Settings Header Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main">Pusat Konfigurasi Variabel Website</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">Kelola seluruh headline, deskripsi, alamat email instansi, kontak sosial media, and deep link endpoint yang terhubung di situs landing page LPPM.</p>
        </div>
    </div>

    <!-- Main Settings Form Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Tabs Navigation Selection -->
        <div class="lg:col-span-3 bg-white border border-border-main p-4 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col space-y-1">
            <button @click="tab = 'hero'" 
                    :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'hero', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'hero' }" 
                    class="w-full text-left py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span>Headline Hero</span>
            </button>
            <button @click="tab = 'kontak'" 
                    :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'kontak', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'kontak' }" 
                    class="w-full text-left py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                <span>Kontak LPPM</span>
            </button>
            <button @click="tab = 'sosial'" 
                    :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'sosial', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'sosial' }" 
                    class="w-full text-left py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742a3 3 0 110-3.484M12 14a3 3 0 110-6m3.316 2.742a3 3 0 110 3.484M12 14a9 9 0 00-9 9m9-9a9 9 0 009 9m-9-9v-4" /></svg>
                <span>Media Sosial</span>
            </button>
            <button @click="tab = 'portals'" 
                    :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'portals', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'portals' }" 
                    class="w-full text-left py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                <span>Akses Portal CTA</span>
            </button>
        </div>

        <!-- Right Side: Unified Settings Form Panel -->
        <div class="lg:col-span-9 bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border">
            
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 font-body text-xs">
                @csrf
                @method('PUT')

                <!-- Panel Tab 1: Hero Settings -->
                <div x-show="tab === 'hero'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
                    <h4 class="font-primary font-bold text-base text-text-main pb-2 border-b border-border-main dark:border-dark-border">Konfigurasi Banner Hero Utama</h4>
                    
                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Headline Banner Hero</label>
                        <input type="text" name="hero_headline" required value="{{ $settings['hero_headline'] }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20">
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Subheadline / Deskripsi Pengantar Hero</label>
                        <textarea name="hero_subheadline" required rows="4" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 leading-relaxed">{{ $settings['hero_subheadline'] }}</textarea>
                    </div>
                </div>

                <!-- Panel Tab 2: Contact Settings -->
                <div x-show="tab === 'kontak'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display: none;">
                    <h4 class="font-primary font-bold text-base text-text-main pb-2 border-b border-border-main dark:border-dark-border">Hubungi Kami (Kontak Layanan LPPM)</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat Email LPPM</label>
                            <input type="email" name="lppm_email" required value="{{ $settings['lppm_email'] }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Nomor Telepon Sekretariat</label>
                            <input type="text" name="lppm_phone" required value="{{ $settings['lppm_phone'] }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Alamat Kantor Fisik / Kampus</label>
                        <textarea name="lppm_address" required rows="3" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none leading-relaxed">{{ $settings['lppm_address'] }}</textarea>
                    </div>
                </div>

                <!-- Panel Tab 3: Social Media Settings -->
                <div x-show="tab === 'sosial'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display: none;">
                    <h4 class="font-primary font-bold text-base text-text-main pb-2 border-b border-border-main dark:border-dark-border">Tautan Sosial Media Resmi</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Facebook Page URL</label>
                            <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? '#' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Instagram Profile URL</label>
                            <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '#' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Twitter / X URL</label>
                            <input type="text" name="social_twitter" value="{{ $settings['social_twitter'] ?? '#' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">YouTube Channel URL</label>
                            <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? '#' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Panel Tab 4: Access Portals Settings -->
                <div x-show="tab === 'portals'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display: none;">
                    <h4 class="font-primary font-bold text-base text-text-main pb-2 border-b border-border-main dark:border-dark-border">Tautan Link Tombol CTA Hero Landing</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Portal Penelitian Link</label>
                            <input type="text" name="link_portal_penelitian" value="{{ $settings['link_portal_penelitian'] ?? 'https://penelitian.mahardika.ac.id' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-2">Portal Pengabdian Link (SIMPENGMAS)</label>
                            <input type="text" name="link_portal_pengmas" value="{{ $settings['link_portal_pengmas'] ?? 'https://pengmas.mahardika.ac.id' }}" class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Submit trigger button -->
                <div class="pt-6 border-t border-border-main dark:border-dark-border flex justify-end">
                    <button type="submit" class="px-6 py-3.5 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft font-primary font-bold text-xs uppercase tracking-wider transition-all duration-150 shadow-subtle">
                        Simpan Semua Pengaturan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
@endsection
