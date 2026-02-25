<?php

use Illuminate\Support\Facades\Route;


Route::post('/tickets', [TicketController::class, 'store']);
