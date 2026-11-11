<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ECatalogController;
use App\Http\Controllers\AdminPenelitianController;
use App\Http\Controllers\AdminPengabdianController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\HakiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Rute pengalihan rute legacy penelitian
Route::get('/penelitian', function () {
    return redirect('/berkas');
});

// Rute Dasbor Dosen (HARUS berada SEBELUM wildcard /dosen/{slug})
Route::get('/dosen/dashboard', function () {
    $user = auth()->user();
    $dosen = $user ? \App\Models\Dosen::where('email', $user->email)->orWhere('nama', $user->name)->first() : null;
    $dosenId = $dosen->id ?? ($user->id ?? 1);
    
    $proposals = \App\Models\Proposal::where('dosen_id', $dosenId)->latest()->get();
    if ($proposals->isEmpty()) {
        $proposals = \App\Models\Proposal::latest()->get();
    }

    $penelitianProposals = $proposals->where('skema', 'Penelitian');
    $pengabdianProposals = $proposals->where('skema', 'Pengabdian');

    $surats = \App\Models\Surat::latest()->get();
    $berkas = \App\Models\BerkasPengajuan::latest()->get();
    
    // Ambil data dari haki_submissions atau fallback ke hkis
    $hakiSubmissions = \App\Models\HakiSubmission::where('dosen_id', $dosenId)->latest()->get();
    if ($hakiSubmissions->isEmpty()) {
        $hakiSubmissions = \App\Models\HakiSubmission::latest()->get();
    }
    $hkis = \App\Models\Hki::where('dosen_id', $dosenId)->latest()->get();

    return view('dosen.dashboard', compact('proposals', 'penelitianProposals', 'pengabdianProposals', 'surats', 'berkas', 'hakiSubmissions', 'hkis'));
})->name('dosen.dashboard');

Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');
Route::get('/direktori-dosen', [App\Http\Controllers\DosenPublicController::class, 'index'])->name('frontend.dosen.index');
Route::get('/dosen', [App\Http\Controllers\DosenPublicController::class, 'index'])->name('frontend.dosen.direct');
Route::get('/dosen/{slug}', [LandingPageController::class, 'lecturerProfile'])->where('slug', '^(?!dashboard|proposal|haki|laporan).*')->name('landing.lecturer');
Route::get('/portal', [LandingPageController::class, 'portal'])->name('portal.index');

Route::get('/katalog', [ECatalogController::class, 'index'])->name('katalog');
Route::get('/e-catalog', [ECatalogController::class, 'index'])->name('catalog.index');
Route::get('/e-catalog/penelitian/{id}', [ECatalogController::class, 'showPenelitian'])->name('catalog.penelitian.show');
Route::get('/e-catalog/pengabdian/{id}', [ECatalogController::class, 'showPengabdian'])->name('catalog.pengabdian.show');
Route::get('/hibah', function() {
    return redirect()->route('dosen.dashboard');
});

// Modul Otomatisasi Persuratan: Verifikasi Dokumen & Cetak PDF Resmi
Route::get('/surat/verify/{hash}', [SuratController::class, 'verify'])->name('surat.verify');
Route::get('/surat/pdf/{id}', [SuratController::class, 'pdf'])->name('surat.pdf');
Route::get('/surat/cetak/{id}', [SuratController::class, 'cetak'])->name('surat.cetak');

// Approval Surat (Fallback route untuk kompatibilitas form surat)
Route::post('/surat/approve/{id}', [SuratController::class, 'approve'])->name('surat.approve');

// Rute Pengajuan HKI Dosen
Route::get('/dosen/haki/create', [App\Http\Controllers\HakiController::class, 'create'])->name('dosen.haki.create');
Route::post('/dosen/haki', [App\Http\Controllers\HakiController::class, 'store'])->name('dosen.haki.store');
Route::post('/dosen/haki/store', [App\Http\Controllers\HakiController::class, 'store']);
Route::get('/dosen/haki/template/{type}', [App\Http\Controllers\HakiController::class, 'downloadTemplate'])->name('dosen.haki.template');
Route::get('/dosen/haki', function() {
    return redirect()->route('dosen.dashboard', ['tab' => 'haki']);
});

/*
|--------------------------------------------------------------------------
| Modul 4: Manajemen Reviewer Proposal (Penugasan & Penilaian)
|--------------------------------------------------------------------------
*/
Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
Route::get('/reviewer/penugasan', [ReviewerController::class, 'index'])->name('reviewer.penugasan.index');
Route::post('/reviewer/penugasan', [ReviewerController::class, 'store'])->name('reviewer.penugasan.store');
Route::put('/reviewer/penugasan/{id}', [ReviewerController::class, 'update'])->name('reviewer.penugasan.update');
Route::post('/reviewer/penugasan/{id}', [ReviewerController::class, 'update'])->name('reviewer.penugasan.update.post');

// Legacy routes for evaluation form
Route::get('/reviewer/dashboard', [ReviewerController::class, 'dashboard'])->name('reviewer.dashboard');
Route::get('/reviewer/penilaian/{id}', [ReviewerController::class, 'penilaian'])->name('reviewer.penilaian');
Route::post('/reviewer/penilaian/{id}', [ReviewerController::class, 'storePenilaian'])->name('reviewer.penilaian.store');

// SSO Routes & Logout
Route::get('/sso/login', [LandingPageController::class, 'ssoLogin'])->name('sso.login');
Route::get('/portal/callback', [LandingPageController::class, 'ssoCallback'])->name('sso.callback');
Route::match(['get', 'post'], '/logout', [LandingPageController::class, 'ssoLogout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Khusus Dosen / User yang Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Rute Dosen: Pengusulan Proposal (Alur BIMA)
    Route::get('/dosen/proposal/create', [ProposalController::class, 'create'])->name('dosen.proposal.create');
    Route::post('/dosen/proposal/store', [ProposalController::class, 'store'])->name('dosen.proposal.store');

    // Rute Dosen: Pelaporan LPJ Terintegrasi (Fase 70% dan 100%)
    Route::get('/dosen/laporan/create', [LaporanController::class, 'create'])->name('dosen.laporan.create');
    Route::post('/dosen/laporan/store', [LaporanController::class, 'store'])->name('dosen.laporan.store');
    
    // Rute Dosen: Pengajuan Dokumen & Laporan
    Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas.index');
    Route::post('/berkas', [BerkasController::class, 'store'])->name('berkas.store');
    
    // Rute Dosen: Pengajuan Surat
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::delete('/surat/{id}', [SuratController::class, 'destroy'])->name('surat.destroy');
});

/*
|--------------------------------------------------------------------------
| Reviewer Routes (Khusus Role Reviewer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:reviewer'])->group(function () {
    Route::get('/reviewer/dashboard', [ReviewerController::class, 'dashboard'])->name('reviewer.dashboard');
    Route::post('/reviewer/penilaian/{id}', [ReviewerController::class, 'storePenilaian'])->name('reviewer.penilaian.store');
});

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
    Route::match(['get', 'post'], '/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Proteksi ketat: Dosen tidak boleh masuk ke panel admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Rute Plotting (Assign) Proposal ke Reviewer
        Route::post('/penelitian/{id}/assign', [AdminPenelitianController::class, 'assignReviewer'])->name('admin.penelitian.assign');

        // Modul Admin Persuratan
        Route::get('/surat', [SuratController::class, 'adminIndex'])->name('admin.surat.index');
        Route::post('/surat/{id}/approve', [SuratController::class, 'approve'])->name('admin.surat.approve');
        Route::post('/surat/approve/{id}', [SuratController::class, 'approve'])->name('admin.surat.approve.legacy');
        Route::post('/surat/{id}/reject', [SuratController::class, 'reject'])->name('admin.surat.reject');
        Route::delete('/surat/{id}', [SuratController::class, 'destroy'])->name('admin.surat.destroy');

        // Modul Admin Verifikasi HKI
        Route::get('/hki', [App\Http\Controllers\AdminHakiController::class, 'index'])->name('admin.hki.index');
        Route::post('/hki/{id}/approve', [App\Http\Controllers\AdminHakiController::class, 'approve'])->name('admin.hki.approve');
        Route::post('/hki/{id}/reject', [App\Http\Controllers\AdminHakiController::class, 'reject'])->name('admin.hki.reject');
        Route::get('/admin/hki', [App\Http\Controllers\AdminHakiController::class, 'index']);
        Route::post('/admin/hki/{id}/approve', [App\Http\Controllers\AdminHakiController::class, 'approve']);
        Route::post('/admin/hki/{id}/reject', [App\Http\Controllers\AdminHakiController::class, 'reject']);

        Route::post('/penelitian/{id}/approve', [AdminPenelitianController::class, 'approve'])->name('admin.penelitian.approve');
        Route::post('/pengabdian/{id}/approve', [AdminPengabdianController::class, 'approve'])->name('admin.pengabdian.approve');

        // Verifikasi Pengumpulan Berkas & LPJ Dosen
        Route::get('/berkas', [AdminController::class, 'berkasIndex'])->name('admin.berkas.index');
        Route::post('/berkas/{id}/status', [AdminController::class, 'updateBerkasStatus'])->name('admin.berkas.updateStatus');
        Route::post('/berkas/status/{id}', [AdminController::class, 'updateBerkasStatus'])->name('berkas.status');
        
        Route::get('/penelitian', [AdminPenelitianController::class, 'index'])->name('admin.penelitian');
        Route::post('/penelitian', [AdminPenelitianController::class, 'store'])->name('admin.penelitian.store');
        Route::put('/penelitian/{id}', [AdminPenelitianController::class, 'update'])->name('admin.penelitian.update');
        Route::delete('/penelitian/{id}', [AdminPenelitianController::class, 'destroy'])->name('admin.penelitian.delete');

        Route::get('/pengabdian', [AdminPengabdianController::class, 'index'])->name('admin.pengabdian');
        Route::post('/pengabdian', [AdminPengabdianController::class, 'store'])->name('admin.pengabdian.store');
        Route::put('/pengabdian/{id}', [AdminPengabdianController::class, 'update'])->name('admin.pengabdian.update');
        Route::delete('/pengabdian/{id}', [AdminPengabdianController::class, 'destroy'])->name('admin.pengabdian.delete');

        Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
        Route::post('/announcements', [AdminController::class, 'announcementCreate'])->name('admin.announcement.create');
        Route::put('/announcements/{id}', [AdminController::class, 'announcementUpdate'])->name('admin.announcement.update');
        Route::delete('/announcements/{id}', [AdminController::class, 'announcementDelete'])->name('admin.announcement.delete');
        
        Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
        Route::get('/lecturers/create', [AdminController::class, 'lecturerCreateForm'])->name('admin.lecturer.create.form');
        Route::post('/lecturers', [AdminController::class, 'lecturerCreate'])->name('admin.lecturer.create');
        Route::put('/lecturers/{slug}', [AdminController::class, 'lecturerUpdate'])->name('admin.lecturer.update');
        Route::delete('/lecturers/{slug}', [AdminController::class, 'lecturerDelete'])->name('admin.lecturer.delete');
        
        Route::get('/documents', [AdminController::class, 'documents'])->name('admin.documents');
        Route::post('/documents', [AdminController::class, 'documentCreate'])->name('admin.document.create');
        Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('admin.document.update');
        Route::delete('/documents/{id}', [AdminController::class, 'documentDelete'])->name('admin.document.delete');
        
        Route::get('/journals', [JournalController::class, 'index'])->name('admin.journals');
        Route::post('/journals', [JournalController::class, 'store'])->name('admin.journal.store');
        Route::match(['POST', 'PUT'], '/journals/{id?}', [JournalController::class, 'update'])->name('admin.journal.update');
        Route::match(['POST', 'PUT'], '/journals/update', [JournalController::class, 'update'])->name('admin.journals.update');
        Route::delete('/journals/{id}', [JournalController::class, 'destroy'])->name('admin.journal.destroy');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('admin.settings.update');
        Route::post('/reset', [AdminController::class, 'resetData'])->name('admin.reset');
    });
});

Route::get('/bypass-proposal', function () {
    \Illuminate\Support\Facades\Artisan::call('route:clear'); // Bersihkan cache sekalian
    
    $dosenId = \App\Models\Dosen::first()->id ?? 1;
    $periodeId = \App\Models\Periode::first()->id ?? 1;

    \App\Models\Proposal::create([
        'dosen_id' => $dosenId,
        'periode_id' => $periodeId,
        'judul' => 'Proposal Uji Coba Reviewer ' . rand(100, 999),
        'skema' => 'Penelitian',
        'file_proposal' => 'proposals/dummy_proposal.pdf',
        'file_rab' => 'proposals/dummy_rab.xlsx',
        'status' => 'submitted',
    ]);

    return redirect('/admin/penelitian')->with('success', 'Berhasil! Proposal Dummy siap di-Assign ke Reviewer.');
});

Route::get('/login-reviewer', function () {
    $user = \App\Models\User::where('email', 'reviewer@mahardika.ac.id')->first();
    
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Dr. Reviewer LPPM',
            'email' => 'reviewer@mahardika.ac.id',
            'password' => bcrypt('12345678'),
            'role' => 'reviewer',
        ]);
    } else {
        $user->update(['role' => 'reviewer']);
    }

    \Illuminate\Support\Facades\Auth::login($user);
    return redirect('/reviewer/dashboard')->with('success', 'Berhasil otomatis login sebagai Reviewer!');
});
