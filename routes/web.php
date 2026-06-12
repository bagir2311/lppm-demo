<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');
Route::get('/dosen/{slug}', [LandingPageController::class, 'lecturerProfile'])->name('landing.lecturer');
Route::get('/portal', [LandingPageController::class, 'portal'])->name('portal.index');

/*
|--------------------------------------------------------------------------
| Keycloak SSO Placeholder Routes
|--------------------------------------------------------------------------
*/
Route::get('/sso/login', [LandingPageController::class, 'ssoLogin'])->name('sso.login');
Route::get('/portal/callback', [LandingPageController::class, 'ssoCallback'])->name('sso.callback');
Route::post('/logout', [LandingPageController::class, 'ssoLogout'])->name('sso.logout');

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Admin Auth
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Admin Authenticated Dashboard Group (Managed via base Controller checks)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // CRUD: Announcements
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminController::class, 'announcementCreate'])->name('admin.announcement.create');
    Route::put('/announcements/{id}', [AdminController::class, 'announcementUpdate'])->name('admin.announcement.update');
    Route::delete('/announcements/{id}', [AdminController::class, 'announcementDelete'])->name('admin.announcement.delete');
    
    // CRUD: Lecturers
    Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
    Route::post('/lecturers', [AdminController::class, 'lecturerCreate'])->name('admin.lecturer.create');
    Route::put('/lecturers/{slug}', [AdminController::class, 'lecturerUpdate'])->name('admin.lecturer.update');
    Route::delete('/lecturers/{slug}', [AdminController::class, 'lecturerDelete'])->name('admin.lecturer.delete');
    
    // CRUD: Documents/Regulations
    Route::get('/documents', [AdminController::class, 'documents'])->name('admin.documents');
    Route::post('/documents', [AdminController::class, 'documentCreate'])->name('admin.document.create');
    Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('admin.document.update');
    Route::delete('/documents/{id}', [AdminController::class, 'documentDelete'])->name('admin.document.delete');
    
    // CRUD: Portals/Journals
    Route::get('/journals', [AdminController::class, 'journals'])->name('admin.journals');
    Route::post('/journals', [AdminController::class, 'journalUpdate'])->name('admin.journals.update');
    
    // Edit: Landing Page Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('admin.settings.update');
    
    // Utility: Reset session data to defaults
    Route::post('/reset', [AdminController::class, 'resetData'])->name('admin.reset');
});
