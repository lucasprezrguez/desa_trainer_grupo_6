<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\UserController;


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

Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Ruta de prueba para verificar la configuración de Mailtrap en Laravel
// ----------------------------------------------------------------------
// Esta ruta ha sido creada con el fin de enviar un correo de prueba y 
// comprobar que el servicio de envío de correos está correctamente 
// configurado utilizando Mailtrap como servidor SMTP de pruebas. 
// Mailtrap permite capturar y visualizar correos electrónicos sin 
// enviarlos a destinatarios reales, lo que resulta útil en entornos de 
// desarrollo.
//
// Al acceder a esta ruta en el navegador, se enviará un correo 
// electrónico con un mensaje de prueba a la dirección especificada 
// en el código ('test@desatrainer.com'). Una vez ejecutada la ruta, 
// el mensaje "Correo de prueba enviado con éxito." se mostrará en el 
// navegador si el envío ha sido exitoso.
//
// Nota: Esta ruta debe eliminarse antes de subir el proyecto a 
// producción para evitar accesos indeseados a la función de prueba de 
// envío de correos.
Route::get('/test-email', function () {
    Mail::raw('Este es un correo de prueba de DESA Trainer', function ($message) {
        $message->to('test@desatrainer.com')
                ->subject('Prueba de correo en Laravel');
    });
    return 'Correo de prueba enviado con éxito.';
});