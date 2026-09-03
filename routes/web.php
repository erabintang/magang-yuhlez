<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Root\{
    DashboardController as RootDashboardController,
    AccountController,
    ProgramController as RootProgramController,
    ApplicationController as RootApplicationController,
    CertificateController as RootCertificateController,
    NotificationController as RootNotificationController,
    HomepageController as RootHomepageController
};
use App\Http\Controllers\Company\{
    DashboardController as CompanyDashboardController,
    ProgramController as CompanyProgramController,
    ApplicationController as CompanyApplicationController,
    ProfileController as CompanyProfileController,
    WorkController as CompanyWorkController,
    CertificateController as CompanyCertificateController
};
use App\Http\Controllers\Intern\{
    DashboardController as InternDashboardController,
    ProgramController as InternProgramController,
    ApplicationController as InternApplicationController,
    ProfileController as InternProfileController,
    CertificateController as InternCertificateController
};
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC PAGES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/magang', [PublicController::class, 'programs'])->name('public.programs');
Route::get('/magang/{slug}', [PublicController::class, 'programShow'])->name('public.program.show');
Route::get('/perusahaan', [PublicController::class, 'companies'])->name('public.companies');
Route::get('/perusahaan/{slug}', [PublicController::class, 'companyShow'])->name('public.company.show');
Route::get('/karya', [PublicController::class, 'works'])->name('public.works');
Route::get('/karya/{slug}', [PublicController::class, 'workShow'])->name('public.work.show');
Route::get('/intern/{slug}', [PublicController::class, 'internShow'])->name('public.intern.show');

// ==========================================
// AUTH
// ==========================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.post');

// Registration Choice
Route::get('/register', fn() => view('auth.register'))->name('register.choice');

// Registration
Route::get('/register/intern', [\App\Http\Controllers\Auth\RegisterController::class, 'showInternForm'])->name('register.intern');
Route::post('/register/intern', [\App\Http\Controllers\Auth\RegisterController::class, 'registerIntern'])->middleware('throttle:5,1')->name('register.intern.post');
Route::get('/register/company', [\App\Http\Controllers\Auth\RegisterController::class, 'showCompanyForm'])->name('register.company');
Route::post('/register/company', [\App\Http\Controllers\Auth\RegisterController::class, 'registerCompany'])->middleware('throttle:5,1')->name('register.company.post');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/v1/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/v1/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::post('/files/upload/complete', [FileController::class, 'uploadComplete'])->name('files.upload.complete');
    Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
});

// ==========================================
// ROOT / ADMIN DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:ROOT'])->prefix('dashboard/root')->name('root.')->group(function () {
    Route::get('/', [RootDashboardController::class, 'index'])->name('dashboard');

    // Accounts CRUD
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('accounts.show');
    Route::get('/accounts/{id}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // Companies
    Route::get('/companies', [RootDashboardController::class, 'companies'])->name('companies');
    Route::get('/companies/{slug}', [RootDashboardController::class, 'companyShow'])->name('companies.show');
    Route::get('/companies/{slug}/edit', [RootDashboardController::class, 'companyEdit'])->name('companies.edit');

    // Interns
    Route::get('/interns', [RootDashboardController::class, 'interns'])->name('interns');
    Route::get('/interns/{slug}', [RootDashboardController::class, 'internShow'])->name('interns.show');
    Route::get('/interns/{slug}/edit', [RootDashboardController::class, 'internEdit'])->name('interns.edit');

    // Works
    Route::get('/works', [RootDashboardController::class, 'works'])->name('works.index');
    Route::get('/works/{slug}', [RootDashboardController::class, 'workShow'])->name('works.show');
    Route::put('/works/{slug}', [RootDashboardController::class, 'workUpdate'])->name('works.update');
    Route::post('/works/{slug}/toggle', [RootDashboardController::class, 'workToggle'])->name('works.toggle');
    Route::delete('/works/{slug}', [RootDashboardController::class, 'workDestroy'])->name('works.destroy');

    // Programs
    Route::get('/programs', [RootProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{slug}', [RootProgramController::class, 'show'])->name('programs.show');
    Route::get('/programs/{slug}/edit', [RootProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{slug}', [RootProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{slug}', [RootProgramController::class, 'destroy'])->name('programs.destroy');

    // Applications
    Route::get('/applications', [RootApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [RootApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/accept', [RootApplicationController::class, 'accept'])->name('applications.accept');
    Route::post('/applications/{id}/reject', [RootApplicationController::class, 'reject'])->name('applications.reject');

    // Certificates (view only - issue dilakukan oleh Company)
    Route::get('/certificates', [RootCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}', [RootCertificateController::class, 'show'])->name('certificates.show');

    // Notifications
    Route::get('/notifications', [RootNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [RootNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [RootNotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Homepage CMS
    Route::get('/homepage', [RootHomepageController::class, 'index'])->name('homepage.index');
    Route::get('/homepage/{key}', [RootHomepageController::class, 'edit'])->name('homepage.edit');
    Route::put('/homepage/{key}', [RootHomepageController::class, 'update'])->name('homepage.update');
    Route::post('/homepage/{key}/toggle', [RootHomepageController::class, 'toggle'])->name('homepage.toggle');
    // Section-specific updates
    Route::post('/homepage/hero', [RootHomepageController::class, 'updateHero'])->name('homepage.hero');
    Route::post('/homepage/about', [RootHomepageController::class, 'updateAbout'])->name('homepage.about');
    Route::post('/homepage/team', [RootHomepageController::class, 'updateTeam'])->name('homepage.team');
    Route::post('/homepage/team/remove', [RootHomepageController::class, 'removeTeamMember'])->name('homepage.team.remove');
    Route::post('/homepage/services', [RootHomepageController::class, 'updateService'])->name('homepage.services');
    Route::post('/homepage/services/remove', [RootHomepageController::class, 'removeService'])->name('homepage.services.remove');
    Route::post('/homepage/contributors', [RootHomepageController::class, 'updateContributor'])->name('homepage.contributors');
    Route::post('/homepage/contributors/remove', [RootHomepageController::class, 'removeContributor'])->name('homepage.contributors.remove');
    Route::post('/homepage/cta', [RootHomepageController::class, 'updateCta'])->name('homepage.cta');
    Route::post('/homepage/process', [RootHomepageController::class, 'updateProcess'])->name('homepage.process');
});

// ==========================================
// COMPANY DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:COMPANY'])->prefix('dashboard/company')->name('company.')->group(function () {
    Route::get('/', [CompanyDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [CompanyProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');

    // Programs CRUD
    Route::get('/programs', [CompanyProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [CompanyProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [CompanyProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{slug}', [CompanyProgramController::class, 'show'])->name('programs.show');
    Route::get('/programs/{slug}/edit', [CompanyProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{slug}', [CompanyProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{slug}', [CompanyProgramController::class, 'destroy'])->name('programs.destroy');

    // Applications
    Route::get('/applications', [CompanyApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [CompanyApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/accept', [CompanyApplicationController::class, 'accept'])->name('applications.accept');
    Route::post('/applications/{id}/reject', [CompanyApplicationController::class, 'reject'])->name('applications.reject');

    // Works / Karya CRUD
    Route::get('/works', [CompanyWorkController::class, 'index'])->name('works.index');
    Route::get('/works/create', [CompanyWorkController::class, 'create'])->name('works.create');
    Route::post('/works', [CompanyWorkController::class, 'store'])->name('works.store');
    Route::get('/works/{slug}', [CompanyWorkController::class, 'show'])->name('works.show');
    Route::get('/works/{slug}/edit', [CompanyWorkController::class, 'edit'])->name('works.edit');
    Route::put('/works/{slug}', [CompanyWorkController::class, 'update'])->name('works.update');
    Route::post('/works/{slug}/toggle', [CompanyWorkController::class, 'toggle'])->name('works.toggle');
    Route::delete('/works/{slug}', [CompanyWorkController::class, 'destroy'])->name('works.destroy');
    Route::post('/works/{slug}/gallery', [CompanyWorkController::class, 'addGallery'])->name('works.gallery.add');
    Route::delete('/works/{slug}/gallery/{galleryId}', [CompanyWorkController::class, 'removeGallery'])->name('works.gallery.remove');
    Route::post('/works/{slug}/interns', [CompanyWorkController::class, 'addIntern'])->name('works.interns.add');
    Route::delete('/works/{slug}/interns/{internId}', [CompanyWorkController::class, 'removeIntern'])->name('works.interns.remove');

    // Certificates (Company yang menerbitkan)
    Route::get('/certificates', [CompanyCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CompanyCertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [CompanyCertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{id}', [CompanyCertificateController::class, 'show'])->name('certificates.show');
    Route::post('/certificates/{id}/issue', [CompanyCertificateController::class, 'issue'])->name('certificates.issue');

    // Work Submissions (intern mengirim karya - company review)
    Route::get('/submissions', [\App\Http\Controllers\Company\WorkSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{id}', [\App\Http\Controllers\Company\WorkSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{id}/accept', [\App\Http\Controllers\Company\WorkSubmissionController::class, 'accept'])->name('submissions.accept');
    Route::post('/submissions/{id}/reject', [\App\Http\Controllers\Company\WorkSubmissionController::class, 'reject'])->name('submissions.reject');

    // Interns (view participants)
    Route::get('/interns', [CompanyProfileController::class, 'interns'])->name('interns.index');
    Route::delete('/interns/{programId}/{internId}', [CompanyProfileController::class, 'removeIntern'])->name('interns.remove');

    // Tasks (Company mengirim tugas ke intern)
    Route::get('/tasks', [\App\Http\Controllers\Company\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [\App\Http\Controllers\Company\TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [\App\Http\Controllers\Company\TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [\App\Http\Controllers\Company\TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{id}/toggle', [\App\Http\Controllers\Company\TaskController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::delete('/tasks/{id}', [\App\Http\Controllers\Company\TaskController::class, 'destroy'])->name('tasks.destroy');

    // Notifications
    Route::get('/notifications', [CompanyProfileController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [CompanyProfileController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [CompanyProfileController::class, 'markAllNotificationsRead'])->name('notifications.readAll');
});

// ==========================================
// INTERN DASHBOARD
// ==========================================
Route::middleware(['auth', 'role:INTERN'])->prefix('dashboard/intern')->name('intern.')->group(function () {
    Route::get('/', [InternDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [InternProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [InternProfileController::class, 'update'])->name('profile.update');

    // Programs
    Route::get('/programs', [InternProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{slug}', [InternProgramController::class, 'show'])->name('programs.show');

    // Applications
    Route::get('/applications', [InternApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [InternApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications', [InternApplicationController::class, 'store'])->middleware('intern.profile.complete')->name('applications.store');
    Route::post('/applications/{id}/cancel', [InternApplicationController::class, 'cancel'])->name('applications.cancel');

    // Certificates
    Route::get('/certificates', [InternCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}/download', [InternCertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{id}/pdf', [InternCertificateController::class, 'pdf'])->name('certificates.pdf');

    // Works - Karya yang diikuti
    Route::get('/works', [InternDashboardController::class, 'works'])->name('works.index');

    // Works CRUD - Intern buat karya sendiri
    Route::get('/works/create', [\App\Http\Controllers\Intern\WorkController::class, 'create'])->name('works.create');
    Route::post('/works', [\App\Http\Controllers\Intern\WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{slug}/edit', [\App\Http\Controllers\Intern\WorkController::class, 'edit'])->name('works.edit');
    Route::put('/works/{slug}', [\App\Http\Controllers\Intern\WorkController::class, 'update'])->name('works.update');
    Route::delete('/works/{slug}', [\App\Http\Controllers\Intern\WorkController::class, 'destroy'])->name('works.destroy');

    // Work Submissions (intern mengirim karya ke company)
    Route::get('/works/{slug}/submit', [\App\Http\Controllers\Intern\WorkSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/works/{slug}/submit', [\App\Http\Controllers\Intern\WorkSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions', [\App\Http\Controllers\Intern\WorkSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{id}', [\App\Http\Controllers\Intern\WorkSubmissionController::class, 'show'])->name('submissions.show');

    // Tasks (Intern melihat tugas dari company)
    Route::get('/tasks', [\App\Http\Controllers\Intern\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}', [\App\Http\Controllers\Intern\TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{id}/accept', [\App\Http\Controllers\Intern\TaskController::class, 'accept'])->name('tasks.accept');
    Route::post('/tasks/{id}/complete', [\App\Http\Controllers\Intern\TaskController::class, 'complete'])->name('tasks.complete');

    // Notifications
    Route::get('/notifications', [InternDashboardController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [InternDashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [InternDashboardController::class, 'markAllNotificationsRead'])->name('notifications.readAll');
});

