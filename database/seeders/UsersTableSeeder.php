<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles ID
        // 1 -> Administrador
        // 2 -> Empleado
        // 3 -> Cliente

        // Department ID
        // 1 -> Clientes
        // 2 -> Administrador
        // 3 -> Ventas
        // 4 -> Embarques
        // 5 -> Fabricación
        // 6 -> Flotilla

        DB::table('users')->insert([
            'name' => 'Ricardo Monroy',
            'email' => 'rmonroy.rodriguez@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Spawn2001!'),
            'department_id' => 2,
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Fer Prado',
            'email' => 'fer7pradoo@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$4EszsySLSB.HNwEoVv1.V.GQBVILIThFCRFD2Tozxj/9SpacnZE7G',
            'department_id' => 2,
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Guillermo Bringas',
            'email' => 'gbringas@tooring.com.mx',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'department_id' => 3,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Eric Estrada',
            'email' => 'eestrada@tooring.com.mx',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'department_id' => 4,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Javier rico',
            'email' => 'embarques.grupoazero@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$GfOpoXWp6fQVHUlb0NNIkOB9.QFqm/zG4AnaLlis40FwJ3oYCcGuq',
            'department_id' => 4,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Gabriel Ruiz',
            'email' => 'fapgar2001@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$2/.izLZlASBcILIKZpfcfePDGNRUJouqq020ySOGnpTFfUN.xOnB6',
            'department_id' => 5,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Gabriel Moncada',
            'email' => 'ventas1.grupoazero@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$A5TSWLle9xUgpgSXNjwfdu./gHPLgPcu8EiDuN6icG64xZWoYfFBS',
            'department_id' => 3,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Chofer prueba',
            'email' => 'Fernanda.pradoa@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$t.nSvWUEEnfFrXxs8iKRV.7OdXy3d7ymtVOsDpBkoJZzgCfLfNr2y',
            'department_id' => 6,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Chofer chofer',
            'email' => 'chofer@grupoazero.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$VCXalS0WH8GV8FeCYxxPuuOjjYHakYKDEIAcjzeHtl5tWo2qA8sya',
            'department_id' => 1,
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'jose fernando',
            'email' => 'josefernando.grupoazero@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$Vt9nvkeiWYPIdxxVnNaYLuepisa1/O/Fy30K6mR/WucCLUvOM8d8O',
            'department_id' => 6,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Jorge',
            'email' => 'jorgehernandez.grupoazero@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$Vt1XacNLAtUo1D.GD3lH9O73xvFkWk7VKsIdsYHxQvDYOTtTIpoDS',
            'department_id' => 2,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Nuevos
        DB::table('users')->insert([
            'name' => 'Gabriela Perez',
            'email' => 'Grupoazero.auxgerencia@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$ptxuriCA1AIuIqDxrFa/hOvWK8aE94jjoh35EhrTDwgjGlAmQCEji',
            'department_id' => 3,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Victor Garcia',
            'email' => 'grupoazero.f1@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$3ycZrbae6LIE6nB3EgqTdOt1oD0h3Pls9TAwi5g5GJOlqIBOFttg.',
            'department_id' => 3,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => '	Hector Rodriguez',
            'email' => 'ventas5.grupoazero@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$F1tOdCrjyufJ.sqdXIYgqORm7OCkhq/8S9W3AqW9PTzrquIUISFHq',
            'department_id' => 2,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
