<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Único usuário permitido no sistema
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@hospital.com'],
            [
                'name'       => 'Admin',
                'email'      => 'admin@hospital.com',
                'password'   => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('medicos')->insertOrIgnore([
            ['nome' => 'Dr. Carlos Souza',  'email' => 'carlos@hospital.com',  'telefone' => '(85) 99999-1111', 'crm' => 'CRM-12345', 'especialidade' => 'Cardiologia',   'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Dra. Ana Lima',     'email' => 'ana@hospital.com',     'telefone' => '(85) 98888-2222', 'crm' => 'CRM-67890', 'especialidade' => 'Pediatria',     'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Dr. Pedro Alves',   'email' => 'pedro@hospital.com',   'telefone' => '(88) 97777-3333', 'crm' => 'CRM-11223', 'especialidade' => 'Ortopedia',     'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('pacientes')->insertOrIgnore([
            ['nome' => 'Maria Silva',    'email' => 'maria@email.com',   'telefone' => '(85) 91111-1111', 'nascimento' => '1990-03-15', 'tipo_sanguineo' => 'A+', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'João Oliveira',  'email' => 'joao@email.com',    'telefone' => '(85) 92222-2222', 'nascimento' => '1985-07-22', 'tipo_sanguineo' => 'O-', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Ana Souza',      'email' => 'anasouza@email.com','telefone' => '(88) 93333-3333', 'nascimento' => '2000-06-05', 'tipo_sanguineo' => 'B+', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('consultas')->insertOrIgnore([
            ['medico_id' => 1, 'paciente_id' => 1, 'data' => '2026-07-10', 'horario' => '09:00:00', 'status' => 'agendada',  'observacoes' => null,      'created_at' => now(), 'updated_at' => now()],
            ['medico_id' => 2, 'paciente_id' => 2, 'data' => '2026-07-10', 'horario' => '11:00:00', 'status' => 'agendada',  'observacoes' => 'Retorno', 'created_at' => now(), 'updated_at' => now()],
            ['medico_id' => 3, 'paciente_id' => 3, 'data' => '2026-07-05', 'horario' => '14:00:00', 'status' => 'concluida', 'observacoes' => null,      'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}