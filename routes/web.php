<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DESAController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\ScenarioController;
use App\Models\Scenario;
use App\Http\Middleware\Roles;
use App\Models\Instruction;
use App\Models\ScenarioInstruction;

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
    Route::get('panel', function () {
        return view('admin.dashboard');
    })->name('dashboard')->middleware(Roles::class);

    // Rutas de usuarios con middleware de autenticación
    Route::middleware(Roles::class)->prefix('panel')->group(function () {

        // Rutas de usuarios con middleware de autenticación
        Route::resource('usuarios', UserController::class)
            ->parameters(['usuarios' => 'user']) // Mapea el parámetro 'usuarios' a 'user' en el controlador
            ->names([
                'index'   => 'users.index',       // Listar todos los usuarios registrados en el sistema
                'create'  => 'users.create',      // Mostrar el formulario para crear un nuevo usuario
                'store'   => 'users.store',       // Guardar un nuevo usuario en la base de datos
                'show'    => 'users.show',        // Mostrar los detalles de un usuario específico
                'edit'    => 'users.edit',        // Mostrar el formulario para editar un usuario existente
                'update'  => 'users.update',      // Actualizar los datos de un usuario existente
                'destroy' => 'users.destroy',     // Eliminar un usuario del sistema
            ]);
        Route::post('/users/{user}/generate-password', [UserController::class, 'generatePassword'])->name('users.generatePassword'); // Generar una nueva contraseña para un usuario específico

        // Rutas de escenarios con middleware de autenticación
        Route::resource('escenarios', ScenarioController::class)
            ->parameters(['escenarios' => 'scenario']) // Mapea el parámetro 'escenarios' a 'scenario' en el controlador
            ->names([
                'index' => 'scenarios.index',      // Listar todos los escenarios disponibles
                'create' => 'scenarios.create',    // Mostrar el formulario para crear un nuevo escenario
                'store' => 'scenarios.store',      // Guardar un nuevo escenario en la base de datos
                'show' => 'scenarios.show',        // Mostrar los detalles de un escenario específico
                'edit' => 'scenarios.edit',        // Mostrar el formulario para editar un escenario existente
                'update' => 'scenarios.update',    // Actualizar los datos de un escenario existente
                'destroy' => 'scenarios.destroy',  // Eliminar un escenario del sistema
            ]);

        // Rutas de instrucciones con middleware de autenticación
        Route::resource('instrucciones', InstructionController::class)
            ->parameters(['instrucciones' => 'instructions']) // Mapea el parámetro 'instrucciones' a 'instructions' en el controlador
            ->names([
                'index' => 'instructions.index',      // Listar todas las instrucciones disponibles
                'create' => 'instructions.create',    // Mostrar el formulario para crear una nueva instrucción
                'store' => 'instructions.store',      // Guardar una nueva instrucción en la base de datos
                'show' => 'instructions.show',        // Mostrar los detalles de una instrucción específica
                'edit' => 'instructions.edit',        // Mostrar el formulario para editar una instrucción existente
                'update' => 'instructions.update',    // Actualizar los datos de una instrucción existente
                'destroy' => 'instructions.destroy',  // Eliminar una instrucción del sistema
            ]);
    });

    // Rutas de vistas del entrenador
    Route::get('trainer', function(){
        $scenarios = Scenario::all();
        $instructions = Instruction::all();
        $scenarioInstruction = ScenarioInstruction::all();
        return view('/trainer/aed', compact('scenarios', 'scenarioInstruction', 'instructions'));
    })->name('trainer.aed');
});