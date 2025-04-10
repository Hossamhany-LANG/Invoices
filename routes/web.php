<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ClientsReportsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckPermission;
use App\Http\Controllers\InvoicesreportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyUser;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard',[DashboardController::class , 'index'])
->middleware(['auth', 'verified' ,VerifyUser::class])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('invoices' , InvoiceController::class);
Route::resource('sections' , SectionController::class);
Route::resource('products' , ProductController::class);
Route::get('/section/{id}', [InvoiceController::class, 'getproducts']);
Route::resource('invoicesdetails' , InvoicesDetailsController::class);
Route::post('delete_file', [InvoicesDetailsController::class , 'destroy'])->name('delete_file');
Route::get('viewfile/{invoice_number}/{file_name}' ,[InvoicesDetailsController::class , 'open_file']);
Route::get('download/{invoice_number}/{file_name}' ,[InvoicesDetailsController::class , 'get_file']);
Route::resource('InvoiceAttachments' , InvoicesAttachmentsController::class);
Route::get('edit_invoice/{id}' , [InvoiceController::class , 'edit']);
Route::match(['GET', 'POST'], 'Status_show/{id}', [InvoiceController::class, 'show'])->name('Status_show');
Route::post('status_update/{id}' , [InvoiceController::class , 'status_update'])->name('status_update');
Route::get('paid_invoices' , [InvoiceController::class , 'paid_invoices']);
Route::get('unpaid_invoices' , [InvoiceController::class , 'unpaid_invoices']);
Route::get('partly_paid_invoices' , [InvoiceController::class , 'partly_paid_invoices']);
Route::resource('archive_invoices' , ArchiveController::class);
Route::get('print_invoice/{id}' , [InvoiceController::class , 'print_invoice'])->name('print_invoice');
Route::get('/invoices-export', [InvoiceController::class, 'export'])->name('invoices.export');
Route::get('invoices_report', [InvoicesreportController::class, 'index']);
Route::post('Search_invoices', [InvoicesreportController::class , 'Search_invoices']);
Route::get('clients_report', [ClientsReportsController::class, 'index']);
Route::post('Search_clients', [ClientsReportsController::class , 'Search_clients']);
Route::get('MarkAsRead',[InvoiceController::class, 'MarkAsRead'])->name('MarkAsRead');



Route::resource('users' , UserController::class)->middleware('auth' , CheckPermission::class);
Route::resource('roles' , RoleController::class)->middleware('auth' , CheckPermission::class);



/* ----------------------------------------------------------------*/
require __DIR__.'/auth.php';
Route::get('/{page}',[AdminController::class ,'index']);