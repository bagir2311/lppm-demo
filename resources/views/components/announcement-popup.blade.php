<div x-data="{ 
        showPopup: false, 
        dontShowAgain: false,
        initPopup() {
            const closedAt = localStorage.getItem('lppm_popup_closed_at');
            const now = new Date().getTime();
            // Show popup if not closed, or if 24 hours have passed since last closure
            if (!closedAt || (now - parseInt(closedAt)) > (24 * 60 * 60 * 1000)) {
                setTimeout(() => this.showPopup = true, 1500);
            }
        },
        closePopup() {
            this.showPopup = false;
            if (this.dontShowAgain) {
                localStorage.setItem('lppm_popup_closed_at', new Date().getTime().toString());
            }
        }
     }" 
     x-init="initPopup()"
     @keydown.escape.window="closePopup()"
     x-show="showPopup"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
     
    <!-- Backdrop overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" 
         x-show="showPopup"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closePopup()"></div>

    <!-- Modal position wrapper -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        
        <!-- Modal panel content -->
        <div class="relative transform overflow-hidden rounded-3xl bg-bg-surface border border-border-main p-6 text-left shadow-floating transition-all duration-300 w-full max-w-lg dark:bg-dark-surface dark:border-dark-border"
             x-show="showPopup"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95">
             
            <!-- Close icon button -->
            <button @click="closePopup()" 
                    class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150"
                    aria-label="Tutup Pengumuman">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Poster Header/Badge -->
            <div class="flex items-center space-x-2.5 mb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-alert-red-pale text-alert-red dark:bg-dark-red-accent/10 dark:text-dark-red-accent">
                    PENGUMUMAN PENTING
                </span>
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-alert-red opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-alert-red"></span>
                </span>
            </div>

            <!-- Content Area -->
            <div class="space-y-4">
                <!-- Visual / Mini Card Representation -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-mahardika-navy to-mahardika-navy-soft p-5 text-white shadow-card">
                    <!-- Shield vector backdrops -->
                    <div class="absolute -right-12 -bottom-12 opacity-10 text-white pointer-events-none">
                        <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    
                    <span class="text-[10px] uppercase font-bold tracking-wider text-gold-accent font-primary">Skema Hibah Akademik 2026</span>
                    <h4 class="font-primary font-bold text-lg leading-snug mt-1 text-white">
                        Penerimaan Proposal Hibah Penelitian & Pengmas Internal TA 2026/2027
                    </h4>
                    
                    <div class="mt-4 flex items-center justify-between text-xs text-mahardika-navy-pale/90 border-t border-white/10 pt-3">
                        <div>
                            <span class="block text-[10px] text-mahardika-navy-pale/60 uppercase">Mulai Pendaftaran</span>
                            <span class="font-bold text-white">18 Mei 2026</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] text-mahardika-navy-pale/60 uppercase">Batas Akhir</span>
                            <span class="font-bold text-amber-300">15 Juni 2026</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <h5 class="font-primary font-bold text-text-main text-sm">Kesempatan Emas Pendanaan Penelitian Dosen</h5>
                    <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        LPPM Universitas Insan Mahardika secara resmi mengundang seluruh dosen tetap bersertifikasi maupun non-sertifikasi untuk mengajukan usulan proposal riset dan pengabdian masyarakat terbaik guna meningkatkan klasterisasi riset nasional institusi.
                    </p>
                </div>
            </div>

            <!-- Footer Checkbox and Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-border-main pt-4 dark:border-dark-border">
                <!-- Checkbox "Don't show again" -->
                <label class="flex items-center space-x-2.5 cursor-pointer self-start sm:self-center">
                    <input type="checkbox" 
                           x-model="dontShowAgain"
                           class="w-4 h-4 rounded border-border-main text-mahardika-navy focus:ring-mahardika-navy dark:border-dark-border dark:focus:ring-navy-accent dark:bg-dark-surface-soft">
                    <span class="font-body text-xs text-text-secondary dark:text-dark-text-muted select-none">Jangan tampilkan lagi hari ini</span>
                </label>

                <!-- Action Button Actions -->
                <div class="flex items-center space-x-2.5 w-full sm:w-auto">
                    <button @click="closePopup()" 
                            class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-xs font-semibold border border-border-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft text-text-main transition-colors duration-150">
                        Nanti Saja
                    </button>
                    <a href="#pengumuman" 
                       @click="closePopup()"
                       class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-xs font-semibold bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle text-center transition-all duration-150 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                        Lihat Panduan
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
