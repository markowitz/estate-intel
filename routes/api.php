<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\ExternalBooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('external-books', [ExternalBooksController::class, 'searchBooks']);
Route::controller(BooksController::class)->prefix('books')->group(function () {
    Route::post('/', 'create');
    Route::get('/', 'read');
    Route::patch('{book}', 'update');
    Route::delete('{book}', 'delete');
    Route::get('{book}', 'show');

});