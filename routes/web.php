<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrivateClassController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gym Management Routes
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule/generate', [ScheduleController::class, 'generateSchedule'])->name('schedule.generate');
    Route::post('/schedule/template', [ScheduleController::class, 'storeTemplate'])->name('schedule.template.store');
    Route::put('/schedule/template/{template}', [ScheduleController::class, 'updateTemplate'])->name('schedule.template.update');
    Route::delete('/schedule/template/{template}', [ScheduleController::class, 'destroyTemplate'])->name('schedule.template.destroy');
    
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    
    Route::get('/private-classes', [PrivateClassController::class, 'index'])->name('private-classes');
    Route::post('/private-classes', [PrivateClassController::class, 'store'])->name('private-classes.store');
    Route::put('/private-classes/{privateClass}', [PrivateClassController::class, 'update'])->name('private-classes.update');
    Route::delete('/private-classes/{privateClass}', [PrivateClassController::class, 'destroy'])->name('private-classes.destroy');
    
    // Leads Module
    Route::get('/leads', [LeadController::class, 'index'])->name('leads');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
    Route::post('/leads/{lead}/ai-enable', [LeadController::class, 'enableAi'])->name('leads.ai-enable');
    Route::post('/leads/{lead}/ai-disable', [LeadController::class, 'disableAi'])->name('leads.ai-disable');
    Route::get('/leads/{lead}/ai-preview', [LeadController::class, 'aiPreview'])->name('leads.ai-preview');
    
    // Locations Module
    Route::get('/locations', [LocationController::class, 'index'])->name('locations');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    
    // Organization Settings
    Route::get('/organization', [OrganizationController::class, 'show'])->name('organization');
    Route::put('/organization', [OrganizationController::class, 'update'])->name('organization.update');
    Route::post('/organization/switch-plan', [OrganizationController::class, 'switchPlan'])->name('organization.switch-plan');
    
    // Members Module
    Route::get('/members', [MemberController::class, 'index'])->name('members');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/freeze', [MemberController::class, 'freeze'])->name('members.freeze');
    Route::post('/members/{member}/cancel', [MemberController::class, 'cancel'])->name('members.cancel');
    
    // Reports
    Route::get('/reports', fn() => inertia('Reports'))->name('reports');
    Route::get('/reports/overview', [ReportController::class, 'overview'])->name('reports.overview');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/members', [ReportController::class, 'members'])->name('reports.members');
    Route::get('/reports/leads', [ReportController::class, 'leads'])->name('reports.leads');
    
    // Attendance
    Route::get('/attendance', fn() => inertia('Attendance'))->name('attendance');
    
    // Exports
    Route::get('/export/members', [ExportController::class, 'members'])->name('export.members');
    Route::get('/export/leads', [ExportController::class, 'leads'])->name('export.leads');
    Route::get('/export/payments', [ExportController::class, 'payments'])->name('export.payments');
});

require __DIR__.'/auth.php';

// AI Lead Automation Schedule (run hourly)

Route::post('/api/login', [SimpleLoginController::class, 'login']);

