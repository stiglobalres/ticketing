<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware('auth')->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('tickets');
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/getTickets', [TicketController::class, 'getTickets'])->name('tickets.gettickets');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/tickets/edit/{id}', [TicketController::class, 'edit'])->name('tickets.edit.{id}');
    Route::post('/tickets/update/{id}', [TicketController::class, 'update'])->name('tickets.update.{id}');
    Route::get('/tickets/info/{id}', [TicketController::class, 'info'])->name('tickets.info.{id}');

});


require __DIR__.'/auth.php';
