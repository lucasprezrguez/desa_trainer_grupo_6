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
    Route::get('panel', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Route::middleware(['auth', 'verified'])->prefix('panel')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('admin.dashboard');
// });

Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::resource('usuarios', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);
    Route::post('/usuarios/{user}/generate-password', [UserController::class, 'generatePassword'])->name('users.generatePassword');
});

//DESA
Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::resource('dispositivos', DESAController::class)->names([
        'index' => 'devices.index',
        'create' => 'devices.create',
        'store' => 'devices.store',
        'show' => 'devices.show',
        'edit' => 'devices.edit',
        'update' => 'devices.update',
        'destroy' => 'devices.destroy',
    ]);
});

//Scenario
Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::resource('escenarios', ScenarioController::class)->names([
        'index' => 'scenarios.index',
        'create' => 'scenarios.create',
        'store' => 'scenarios.store',
        'show' => 'scenarios.show',
        'edit' => 'scenarios.edit',
        'update' => 'scenarios.update',
        'destroy' => 'scenarios.destroy',
    ]);
});

//Instruction
Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::resource('instrucciones', InstructionController::class)->names([
        'index' => 'instructions.index',
        'create' => 'instructions.create',
        'store' => 'instructions.store',
        'show' => 'instructions.show',
        'edit' => 'instructions.edit',
        'update' => 'instructions.update',
        'destroy' => 'instructions.destroy',
    ]);
});

Route::get('trainer', function(){
    return view('/trainer/index');
});

// Add the new route for 'aed.blade.php'
Route::get('trainer/aed', function(){
    return view('/trainer/aed');
});