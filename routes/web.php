<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index']);

Route::resource('publisher', PublisherController::class);

Route::resource('catalog', CatalogController::class);

Route::resource('/author', AuthorController::class);
Route::get('/api/author', [AuthorController::class, 'api']);

Route::resource('/books', BookController::class);
Route::get('/api/books', [BookController::class, 'api']);

Route::resource('/members', MemberController::class);
Route::get('/api/members', [MemberController::class, 'api']);

Route::resource('/transactions', TransactionController::class);
Route::get('/api/transactions', [TransactionController::class, 'api']);
Route::get('/api/transactiondetails', [TransactionDetailController::class, 'api']);

Route::get('test_spatie', [AdminController::class, 'test_spatie']);
