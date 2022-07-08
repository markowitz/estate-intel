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
Route::post('books', [BooksController::class, 'create']);
Route::get('books', [BooksController::class, 'read']);
Route::patch('books/{book}', [BooksController::class, 'update']);
Route::delete('books/{book}', [BooksController::class, 'delete']);
Route::get('books/{book}', [BooksController::class, 'show']);
