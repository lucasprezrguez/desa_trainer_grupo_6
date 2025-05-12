<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DESAController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\AdminController; // <- Añadir esta línea
use App\Models\Scenario;
use App\Http\Middleware\Roles;
use App\Models\Instruction;
use App\Models\ScenarioInstruction;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta modificada para usar el AdminController
    Route::get('panel', [AdminController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware(Roles::class);

    Route::middleware(Roles::class)->prefix('panel')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->parameters(['usuarios' => 'user'])
            ->names([
                'index'   => 'users.index',
                'create'  => 'users.create',
                'store'   => 'users.store',
                'show'    => 'users.show',
                'edit'    => 'users.edit',
                'update'  => 'users.update',
                'destroy' => 'users.destroy',
            ]);
            
        Route::post('/users/{user}/generate-password', [UserController::class, 'generatePassword'])
            ->name('users.generatePassword');

        Route::resource('escenarios', ScenarioController::class)
            ->parameters(['escenarios' => 'scenario'])
            ->names([
                'index' => 'scenarios.index',
                'create' => 'scenarios.create',
                'store' => 'scenarios.store',
                'show' => 'scenarios.show',
                'edit' => 'scenarios.edit',
                'update' => 'scenarios.update',
                'destroy' => 'scenarios.destroy',
            ]);

        Route::resource('instrucciones', InstructionController::class)
            ->parameters(['instrucciones' => 'instructions'])
            ->names([
                'index' => 'instructions.index',
                'create' => 'instructions.create',
                'store' => 'instructions.store',
                'show' => 'instructions.show',
                'edit' => 'instructions.edit',
                'update' => 'instructions.update',
                'destroy' => 'instructions.destroy',
            ]);
        
        // Nueva rota para actualizar BPM
        Route::post('update-bpm', [AdminController::class, 'updateBpm'])
            ->name('update.bpm');
    });

    Route::get('trainer', function(){
        $scenarios = Scenario::all();
        $instructions = Instruction::all();
        $scenarioInstruction = ScenarioInstruction::all();
        return view('/trainer/aed', compact('scenarios', 'scenarioInstruction', 'instructions'));
    })->name('trainer.aed');
});