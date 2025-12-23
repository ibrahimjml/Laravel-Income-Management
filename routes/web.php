<?php

use App\Http\Controllers\Admin\{ActivityController, PaymentController, AdminController, Calendarcontroller, ClientsController,IncomesController,OutcomesController};
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Auth\LoginController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;

Route::get('/',[LoginController::class,'login_page'])->name('login.page');
Route::post('/login',[LoginController::class,'login'])->name('admin.login');
Route::post('/logout',[LoginController::class,'logout'])->name('logout');

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(), 
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath','setLocale']
    ], 
    function() {

Route::prefix('admin')
->middleware('auth')
->group(function(){
Route::controller(AdminController::class)->group(function(){
  Route::get('/dashboard', 'index')->name('dashboard');
  Route::get('/clients', 'clients_page')->name('admin.clients');
  Route::get('/incomes', 'incomes_page')->name('admin.incomes');
  Route::get('/outcomes', 'outcomes_page')->name('admin.outcomes');
  Route::get('/reports', 'reports_page')->name('admin.reports');
});
Route::controller(ClientsController::class)->group(function(){
  // All clients
  Route::post('/add-client-type', 'add_client_type')->name('add.client.type');
  Route::put('/edit-type/{id}', 'edit_client_type')->name('edit.type');
  Route::delete('/delete-type/{id}', 'delete_type')->name('delete.type');
  Route::post('/add-client', 'add_client')->name('add.client');
  Route::put('/edit-client/{id}', 'edit_client')->name('edit.client');
  Route::put('/delete-client/{id}', 'delete_client')->name('delete.client');
  // Recover clients
  Route::get('/clients/trashed','trashed_clients')->name('trashed.clients');
  Route::patch('/clients/recover/{id}','recover')->name('client.recover');
  Route::delete('/clients/delete/{id}','force_delete')->name('client.force.delete');
});

Route::controller(IncomesController::class)->group(function(){
  // all incomes
  Route::post('/add-category', 'add_category')->name('add.cat');
  Route::post('/add-subcategory', 'add_subcategory')->name('add.sub');
  Route::post('/delete-income/{income}', 'delete')->name('delete.inc');
  Route::post('/add-income', 'add_income')->name('add.inc');
  Route::put('/edit-income/{income}', 'update')->name('update.inc');
  Route::get('/details/{income}' ,'show')->name('details');
  // Recover Incomes
});
Route::controller(PaymentController::class)->group(function(){
  // All payments
    Route::get('/payments', 'payments_page')->name('admin.payments');
    Route::post('/add-payment/{income}', 'add_payment')->name('add.payment');
    Route::put('/edit-payment/{payment}/{income}', 'edit_payment')->name('edit.payment');
    Route::delete('/delete-payment/{payment}/{income}','delete_payment')->name('delete.payment');
    Route::get('/outdatedpayments', 'outdated_page')->name('admin.outdated');
    Route::get('/todaypayments', 'today_page')->name('admin.today');
    Route::get('/upcomingpayments', 'upcoming_page')->name('admin.upcoming');
    Route::post('/payment/invoice/{payment}','generate_invoice')->name('payment.invoice');
    // Recover payments
    Route::get('/payments/trashed','trashed_payments')->name('trashed.payments');
    Route::patch('/payments/recover/{id}','recover')->name('payment.recover');
    Route::delete('/payments/delete/{id}','force_delete')->name('payment.force.delete');

});
Route::controller(OutcomesController::class)->group(function(){
  // all outcomes
  Route::post('/outcome/category', 'add_category')->name('add.out.cat');
  Route::post('/outcome/subcategory', 'add_subcategory')->name('add.cat.sub');
  Route::post('/delete-outcome/{outcome}', 'delete')->name('delete.out');
  Route::post('/add-outcome', 'add_outcome')->name('add.out');
  Route::put('/edit-outcome/{id}', 'edit_outcome')->name('edit.out');
  // recover outcomes
  Route::get('/outcomes/trashed','trashed_outcomes')->name('trashed.outcomes');
  Route::patch('/outcomes/recover/{id}','recover')->name('outcome.recover');
  Route::delete('/outcomes/delete/{id}','force_delete')->name('outcome.force.delete');
});

Route::resource('/discounts',DiscountController::class)->except(['create','show','edit']);
// invoices
Route::resource('/invoices',InvoiceController::class);
Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
// calendar events
Route::resource('/calendar',Calendarcontroller::class)->except(['create','show','edit']);
Route::get('/calendar/events',[Calendarcontroller::class,'getEvents'])->name('calendar.events');
Route::put('/event/move/{id}',[Calendarcontroller::class,'move'])->name('calendar.move');
Route::put('/event/resize/{id}',[Calendarcontroller::class,'resize'])->name('calendar.resize');
// activity logs
Route::get('/logs',[ActivityController::class,'index'])->name('activity.logs');
Route::delete('/logs/delete',[ActivityController::class,'delete'])->name('activity.delete');

});
    });