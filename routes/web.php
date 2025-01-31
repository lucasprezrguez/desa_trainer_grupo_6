<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DESAController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\ScenarioController;
use App\Models\Scenario;
use App\Http\Middleware\Roles;

// Redirigir a la página de inicio de sesión
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de middleware para usuarios autenticados y verificados
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
});
Route::get('panel', function () {
    return view('admin.dashboard');
})->name('dashboard')->middleware(Roles::class);

// Rutas de usuarios con middleware de autenticación
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
    Route::post('/users/{user}/generate-password', [UserController::class, 'generatePassword'])->name('users.generatePassword');
});

// Rutas de DESA con middleware de autenticación
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

// Rutas de escenarios con middleware de autenticación
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

// Rutas de instrucciones con middleware de autenticación
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

// Rutas de vistas del entrenador
Route::get('trainer', function(){
    return view('/trainer/index');
});

Route::get('/modal-electrodos', function () {
    return view('trainer.modal-electrodos');
})->name('ruta.modal.electrodos');


Route::get('trainer/aed', function(){
    $scenarios = Scenario::all();
    return view('/trainer/aed', compact('scenarios'));
})->name('trainer.aed');

// Test
Route::get('/electrodo', function () {
    return view('trainer.electrodo'); 
});