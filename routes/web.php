<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainStay\Auth\LoginController;
use App\Http\Controllers\Mainstay\DashBoard\DashboardController;
use App\Http\Controllers\Mainstay\TicketController\TicketController;
use App\Http\Controllers\MainStay\CustomerController\CustomerController;
Route::get('/widget', function () {
    return view('CustomSite.WidgetPage.FeedBackPage');
})->name('widget.feedback');

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'DashBoardAdmin'])->name('dashboard');
    Route::get('/tickets', [TicketController::class, 'adminTicket'])->name('tickets.index');
    Route::get('/customers', [CustomerController::class, 'customerAdmin'])->name('customers.index');
    Route::get('/tickets/details', [TicketController::class, 'adminTicketsDetalis'])->name('tickets.show');
    Route::put('/tickets/{id}', [TicketController::class, 'updateTicket'])->name('tickets.update');
});

Route::redirect('/', '/widget');
