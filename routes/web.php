<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ExcelImportController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::post('/users/import', [UsersController::class, 'import'])->name('users.import');

Route::get('/multiple', [ExcelImportController::class, 'index'])->name('multiple.index');
Route::post('/multiple-import', [ExcelImportController::class, 'import'])->name('multiple.import');
