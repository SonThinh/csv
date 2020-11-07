<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::post('user-import', [UserController::class, 'userImport'])->name('user.import');
Route::get('user-export', [UserController::class, 'userExport'])->name('user.export');