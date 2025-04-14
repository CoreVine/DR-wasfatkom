<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TrashedController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FormulationController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Models\SubCategory;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\HomeController;

Route::get("change-lang/{lang}", [HomeController::class, 'change_lang'])->name('change_lang');
Route::resource('admins', AdminController::class);
Route::post('admins/change-password', [AdminController::class, 'change_password'])->name('admins.change-password');

Route::group(["prefix" => "admins", "as" => "admins."], function () {
  Route::get("docotrs/get-all-doctors", [AdminController::class, "get_doctors"])->name('get_doctors');
  Route::get("export", [AdminController::class, "export"])->name('export');
  Route::post("{id}/block", [AdminController::class, "block"])->name('block');
  Route::post("{id}/unblock", [AdminController::class, "unblock"])->name('unblock');
});

Route::resource('/doctors', DoctorController::class);
Route::group(["prefix" => "doctors", "as" => "doctors."], function () {
  Route::post("{id}/block", [DoctorController::class, "block"])->name('block');
  Route::post("{id}/approve", [DoctorController::class, "approve"])->name('approve');
  Route::post("{id}/unblock", [DoctorController::class, "unblock"])->name('unblock');
  Route::get("export/{id}", [DoctorController::class, "export"])->name('export');
  Route::get("sale/{id}", [DoctorController::class, "sale"])->name('sales');
  Route::get("export_sale/{id}", [DoctorController::class, "export_sale"])->name('export_sales');
});

Route::group(["prefix" => "categories", "as" => "categories."], function () {
  Route::post("active-inactive/{id}", [CategoryController::class, "active_inactive"])->name('active_inactive');
  // Route::post("export", [CategoryController::class, "export"])->name('export');
  Route::get('{id}/products', [CategoryController::class, "show_category_products"])->name('category_products');
});
Route::resource('/categories', CategoryController::class);

Route::group(["prefix" => "products", "as" => "products."], function () {
  Route::get("trashed", [ProductController::class, "trashed"])->name('trashed');
  Route::get("export", [ProductController::class, "export"])->name('export');
  Route::get("ajax-add-or-remove-favorite", [ProductController::class, "ajax_add_or_remove_favorite"])->name('ajax-add-or-remove-favorite');

  Route::post("restore-all", [ProductController::class, "restore_all"])->name('restore_all');
  Route::post("restore/{id}", [ProductController::class, "restore"])->name('restore');

  Route::delete("delete-all", [ProductController::class, "delete_all"])->name('delete_all');
  Route::delete("force-delete/{id}", [ProductController::class, "force_delete"])->name('force_delete');

  Route::post("active-inactive/{product}", [ProductController::class, "active_inactive"])->name('active_inactive');
  Route::post("import", [ProductController::class, "import"])->name('import');
});

Route::resource('/products', ProductController::class);
Route::resource('/packages', PackageController::class);

Route::resource('posts', PostController::class);
Route::group(["prefix" => "posts", "as" => "posts."], function () {
  Route::post("active-inactive/{post}", [PostController::class, "active_inactive"])->name('active_inactive');
  Route::post("restore/{post}", [PostController::class, "restore"])->name('restore');
  Route::post("force-delete/{post}", [PostController::class, "force_delete"])->name('force_delete');
});

Route::group(["prefix" => "sub-categories", "as" => "sub_categories."], function () {
  Route::post("active-inactive/{id}", [SubCategoryController::class, "active_inactive"])->name('active_inactive');
  Route::post("export", [SubCategoryController::class, "export"])->name('export');
});
Route::resource('/sub-categories', SubCategoryController::class)->names('sub_categories');

Route::group(["prefix" => "settings", "as" => "settings."], function () {

  Route::get("/invoices", [SettingController::class, "invoices"])->name('invoices');

  Route::group(["prefix" => "languages", "as" => "languages."], function () {
    Route::get("/", [LanguageController::class, "index"])->name('index');
    Route::post("{id}/active-in-active", [LanguageController::class, "active_in_active"])->name('active_in_active');
    Route::post("{id}/set-default", [LanguageController::class, "set_default"])->name('set_default');
  });

  Route::group(["prefix" => "pages", "as" => "pages."], function () {
    Route::get("{page}", [SettingController::class, "index"])->name("index");
    Route::post("update/{group}", [SettingController::class, "update"])->name("update");
  });
});

Route::group(["prefix" => "trashed", "as" => "trashed."], function () {
  Route::get("posts", [TrashedController::class, "posts"])->name("posts");
});

Route::group(["prefix" => "logs", "as" => "logs."], function () {
  Route::group(["prefix" => "errors", "as" => "errors."], function () {
    Route::get("/", [LogController::class, "error_log"])->name("log");
    Route::post("clear/", [LogController::class, "clear_error_log"])->name("clear");
  });
});

Route::group(["prefix" => "invoices", "as" => "invoices."], function () {
  Route::get("generate-pdf-invoice/{id}", [InvoiceController::class, "pdf"])->name("pdf");
  Route::post("review/{invoice}", [InvoiceController::class, "review"])->name("review");
  Route::post("send/{invoice}", [InvoiceController::class, "send_status"])->name("send_status");
  Route::post("cancel/{invoice}", [InvoiceController::class, "cancel_status"])->name("cancel_status");
  Route::post("under-delivery/{invoice}", [InvoiceController::class, "under_delivery"])->name("under_delivery");
  Route::get("change_invoice_status", [InvoiceController::class, "change_invoice_status"])->name("change_invoice_status");
  Route::get("change_invoice_payment", [InvoiceController::class, "change_invoice_payment"])->name("change_invoice_payment");
  Route::post("send-invoice/{invoice}", [InvoiceController::class, "send_invoice"])->name("send_invoice");
  Route::get("exp-invoices", [InvoiceController::class, "export"])->name("export");
});

Route::resource('invoices', InvoiceController::class);

Route::group(["prefix" => "formulations", "as" => "formulations."], function () {
  Route::post("active-inactive/{formulation}", [FormulationController::class, "active_inactive"])->name('active_inactive');
  Route::get("export", [FormulationController::class, "export"])->name('export');
  Route::post("import", [FormulationController::class, "import"])->name('import');
});
Route::resource('formulations', FormulationController::class)->only(['index', 'create', 'store', 'edit', 'destroy']);
Route::post('formulations/{id}/update', [FormulationController::class, 'update'])->name('formulations.as-upadte');

Route::group(['prefix' => 'coupons', 'as' => 'coupons.', 'controller' => CouponController::class], function () {
  Route::get("export", "export")->name('export');
  Route::post('/{id}/active', 'active')->name('active');
  Route::post('/{id}/deactivate', 'in_active')->name('inactive');
  Route::get("/get-all-doctors", "get_doctors")->name('get_doctors');
});

Route::resource('coupons', CouponController::class)->except('destroy');
Route::resource('suppliers', SupplierController::class)->except(['destroy', 'show']);
Route::get('/get_sub_categories', [ProductController::class, 'get_sub_category'])->name('get_sub_category');
Route::get('/get_sub_categories_file', [ProductController::class, 'get_sub_category_filter'])->name('get_sub_category_filter');
