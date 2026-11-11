<?php
// --- KODE TES (TARUH PALING ATAS) ---
Route::get('/tes', function () {
    return 'BERHASIL! SISTEM ROUTE BERJALAN NORMAL!';
});

Route::get('/penelitian', [\App\Http\Controllers\PenelitianController::class, 'index']);
Route::get('/penelitian/pengajuan', [\App\Http\Controllers\PenelitianController::class, 'create']);
Route::post('/penelitian/pengajuan', [\App\Http\Controllers\PenelitianController::class, 'store']);
// ------------------------------------
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');
Route::get('/dosen/{slug}', [LandingPageController::class, 'lecturerProfile'])->name('landing.lecturer');
Route::get('/portal', [LandingPageController::class, 'portal'])->name('portal.index');

Route::get('/katalog', [ECatalogController::class, 'index'])->name('katalog');
Route::get('/e-catalog', [ECatalogController::class, 'index'])->name('catalog.index');
Route::get('/e-catalog/penelitian/{id}', [ECatalogController::class, 'showPenelitian'])->name('catalog.penelitian.show');
Route::get('/e-catalog/pengabdian/{id}', [ECatalogController::class, 'showPengabdian'])->name('catalog.pengabdian.show');
Route::get('/hibah', [ECatalogController::class, 'hibah'])->name('hibah');

Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
Route::post('/surat/approve/{id}', [SuratController::class, 'approve'])->name('surat.approve');
Route::get('/surat/pdf/{id}', [SuratController::class, 'pdf'])->name('surat.pdf');

Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas.index');
Route::post('/berkas', [BerkasController::class, 'store'])->name('berkas.store');
Route::post('/berkas/status/{id}', [BerkasController::class, 'updateStatus'])->name('berkas.status');

Route::get('/sso/login', [LandingPageController::class, 'ssoLogin'])->name('sso.login');
Route::get('/portal/callback', [LandingPageController::class, 'ssoCallback'])->name('sso.callback');
Route::post('/logout', [LandingPageController::class, 'ssoLogout'])->name('sso.logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Khusus yang sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Rute Dosen (Pengajuan Proposal)
    Route::get('/penelitian', [PenelitianController::class, 'index'])->name('penelitian.index');
    Route::get('/penelitian/pengajuan', [PenelitianController::class, 'create'])->name('penelitian.create');
    Route::post('/penelitian/pengajuan', [PenelitianController::class, 'store'])->name('penelitian.store');

    // Rute Reviewer
    Route::get('/reviewer/dashboard', [ReviewerController::class, 'dashboard'])->name('reviewer.dashboard');
    Route::get('/reviewer/penilaian/{id}', [ReviewerController::class, 'penilaian'])->name('reviewer.penilaian');
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

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
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
        Route::post('/lecturers', [AdminController::class, 'lecturerCreate'])->name('admin.lecturer.create');
        Route::put('/lecturers/{slug}', [AdminController::class, 'lecturerUpdate'])->name('admin.lecturer.update');
        Route::delete('/lecturers/{slug}', [AdminController::class, 'lecturerDelete'])->name('admin.lecturer.delete');
        
        Route::get('/documents', [AdminController::class, 'documents'])->name('admin.documents');
        Route::post('/documents', [AdminController::class, 'documentCreate'])->name('admin.document.create');
        Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('admin.document.update');
        Route::delete('/documents/{id}', [AdminController::class, 'documentDelete'])->name('admin.document.delete');
        
        Route::get('/journals', [AdminController::class, 'journals'])->name('admin.journals');
        Route::post('/journals', [AdminController::class, 'journalUpdate'])->name('admin.journals.update');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('admin.settings.update');
        Route::post('/reset', [AdminController::class, 'resetData'])->name('admin.reset');
    });
});
// Rute Dosen (Letakkan di paling bawah agar pasti terbaca)
Route::get('/penelitian', [\App\Http\Controllers\PenelitianController::class, 'index']);
Route::get('/penelitian/pengajuan', [\App\Http\Controllers\PenelitianController::class, 'create']);
Route::post('/penelitian/pengajuan', [\App\Http\Controllers\PenelitianController::class, 'store']);

