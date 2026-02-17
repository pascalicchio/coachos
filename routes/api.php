<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController as ApiLeadController;
use App\Http\Controllers\Api\ScheduleController as ApiScheduleController;
use App\Http\Controllers\Api\MemberController as ApiMemberController;
use App\Http\Controllers\Api\CheckInController;

// Public routes (would need API token in production)
Route::post('/leads', [ApiLeadController::class, 'store']);
Route::get('/schedule/today', [ApiScheduleController::class, 'today']);
Route::get('/members/qr/{code}', [ApiMemberController::class, 'lookupByQr']);

// Protected routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn() => auth()->user());
    
    // Leads
    Route::get('/leads', [ApiLeadController::class, 'index']);
    Route::put('/leads/{lead}', [ApiLeadController::class, 'update']);
    
    // Schedule
    Route::get('/schedule', [ApiScheduleController::class, 'index']);
    
    // Members
    Route::get('/members', [ApiMemberController::class, 'index']);
    
    // Check-in (mobile)
    Route::get('/checkin/lookup', [CheckInController::class, 'lookup']);
    Route::post('/checkin', [CheckInController::class, 'checkIn']);
    Route::get('/checkin/today', [CheckInController::class, 'today']);
});
