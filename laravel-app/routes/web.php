<?php

use App\Http\Controllers\Admin\TicketAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

Route::get('/widget', [WidgetController::class, 'show']);

Route::get('/dashboard', [TicketAdminController::class, 'index'])
    ->middleware(['auth', 'manager'])
    ->name('dashboard');

Route::middleware(['auth', 'manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/tickets', [TicketAdminController::class, 'index'])
            ->name('tickets.index');

        Route::get('/tickets/{ticket}', [TicketAdminController::class, 'show'])
            ->name('tickets.show');

        Route::patch('/tickets/{ticket}/status', [TicketAdminController::class, 'updateStatus'])
            ->name('tickets.updateStatus');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
