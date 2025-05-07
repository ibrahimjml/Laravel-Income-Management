<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\IncomesController;
use App\Http\Controllers\Admin\OutcomesController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/',[LoginController::class,'login_page'])->name('login.page');
Route::post('/login',[LoginController::class,'login'])->name('admin.login');
Route::post('/logout',[LoginController::class,'logout'])->name('logout');

Route::prefix('admin')
->middleware('auth')
->group(function(){
Route::controller(AdminController::class)->group(function(){
  Route::get('/dashboard', 'index')->name('dashboard');
  Route::get('/clients', 'client')->name('admin.clients');
  Route::get('/incomes', 'income')->name('admin.incomes');
  Route::get('/outcomes', 'outcome')->name('admin.outcomes');
  Route::get('/payments', 'payment')->name('admin.payments');
  Route::get('/reports', 'report')->name('admin.reports');
});
Route::controller(ClientsController::class)->group(function(){
  Route::post('/add-client-type', 'add_type')->name('add.client.type');
  Route::put('/edit-type/{id}', 'edit_type')->name('edit.type');
  Route::delete('/delete-type/{id}', 'delete_type')->name('delete.type');
  Route::post('/add-client', 'add')->name('add.client');
  Route::put('/edit-client/{id}', 'edit')->name('edit.client');
  Route::put('/delete-client/{id}', 'delete')->name('delete.client');
});

Route::controller(IncomesController::class)->group(function(){
  Route::post('/add-category', 'add_category')->name('add.cat');
  Route::post('/add-subcategory', 'add_subcategory')->name('add.sub');
  Route::post('/delete-income/{income}', 'delete')->name('delete.inc');
  Route::post('/add-income', 'add_income')->name('add.inc');
  Route::put('/edit-income/{income}', 'update')->name('update.inc');
  Route::post('/add-payment/{income}', 'add_payment')->name('add.pmt');
  Route::get('/details/{income}' ,'show')->name('details');
});

Route::controller(OutcomesController::class)->group(function(){
  Route::post('/outcome/category', 'add_category')->name('add.out.cat');
  Route::post('/outcome/subcategory', 'add_subcategory')->name('add.cat.sub');
  Route::post('/delete-outcome/{outcome}', 'delete')->name('delete.out');
  Route::post('/add-outcome', 'add_outcome')->name('add.out');
});
});
