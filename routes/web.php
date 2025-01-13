<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DESAController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\ScenarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/generate-password', [UserController::class, 'generatePassword'])->name('users.generatePassword');
});

//DESA
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('devices', DESAController::class);
});

//Scenario
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('scenarios', ScenarioController::class);
});

//Instruction
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('instructions', InstructionController::class);
});