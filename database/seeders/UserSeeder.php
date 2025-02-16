<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        /*
         * Este seeder inicializa la tabla de usuarios con los nombres de los alumnos y profesores de 2.º DAW de IES El Rincón.
         * La inclusión de múltiples registros garantiza que la base de datos esté suficientemente poblada para permitir la prueba
         * y validación de funcionalidades de paginación y gestión de usuarios en la aplicación. Esto es crucial para asegurar
         * que el sistema maneje adecuadamente escenarios de producción con datos representativos.
         */
        $names = [
            'Alejandro Aguiar', 'Acoidan', 'Miguel Ángel', 'Inacora', 'Moisés', 'Francisco', 'Rubén', 'Alejandro Gil',
            'Naiara', 'Rodrigo', 'Antonio', 'Ares', 'Aarón', 'Cristóbal', 'Lucas', 'Adrián', 'Saulo', 'Juan Francisco',
            'Marta', 'José Manuel', 'David', 'Jorge', 'Felipe', 'Sergio Ramos', 'Juanma', 'Lourdes', 'Toñi', 'Maricarmen', 'Marisol'
        ];
        foreach ($names as $name) {
            $email = strtolower(str_replace([' ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú'], ['', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'], $name)) . '@mail.com';
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'roles' => in_array($name, ['Sergio Ramos', 'Juanma', 'Lourdes', 'Toñi', 'Maricarmen', 'Marisol']) ? 'profesor' : 'alumno',
            ]);
        }

        /*
         * Este seeder también crea un usuario administrador inicial con la contraseña 'admin' y el rol 'admin'.
         * La inclusión de este usuario administrador tiene como objetivo acelerar el proceso de configuración inicial,
         * permitiendo que el sistema esté listo para su uso inmediato sin necesidad de registrar un usuario administrador
         * manualmente. Sin embargo, este usuario debe ser eliminado por razones de seguridad en un entorno de producción,
         * ya que representa un riesgo potencial si se deja sin cambios.
         */
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'roles' => 'admin',
        ]);
    }
}