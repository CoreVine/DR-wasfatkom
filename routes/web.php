<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Models\Invoice;
use App\Payments\TamaraTransaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('send-whatsapp', [HomeController::class, 'send_whatsapp'])->name('send-whatsapp');

Route::get('/', function () {
  return redirect("/home");
});

Auth::routes(['logout' => false]);

Route::get("/pending-approval", [HomeController::class, 'pending_approval'])->middleware('is_approved')->name('pending-approval');
Route::any("logout", [LoginController::class, "logout"])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->middleware('is_approved')->name('home');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::group(["prefix" => "payment", "as" => "payment."], function () {
  Route::post("create", [InvoiceController::class, 'create'])->name("create");

  Route::group(["prefix" => "myfatoora", "as" => "myfatoora."], function () {
    Route::get("callback", [InvoiceController::class, 'myfatoora_callback'])->name('callback');
    Route::get("error", [InvoiceController::class, 'myfatoora_callback'])->name('error');
  });

  Route::group(["prefix" => "tamara", "as" => "tamara."], function () {
    Route::get("callback", [InvoiceController::class, 'tamara_callback'])->name('callback');
    Route::get("error", [InvoiceController::class, 'tamara_callback'])->name('error');
    Route::get("error", [InvoiceController::class, 'tamara_callback'])->name('error');
  });
});
Route::get('/invoices/payment/{id}', [InvoiceController::class, 'payment'])->name('invoices.payment');
Route::get('/mf-callback', [InvoiceController::class, 'mf_callback'])->name('mf-callback');
Route::get('/check-coupon', [InvoiceController::class, 'check_coupon'])->name('check_coupon');

Route::get('/test-tamara', function () {
  return TamaraTransaction::create(Invoice::first());
});
