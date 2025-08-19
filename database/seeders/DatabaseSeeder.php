<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\TipoEntradaSaida;
use App\Models\CategoriaEntradaSaidaModel;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name'     => 'Guilherme Mandeli',
            'email'    => 'guimandeli.santos@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'name'     => 'Jessica Balan',
            'email'    => 'jess@gmail.com',
            'password' => bcrypt('12345678'),
        ]);



        Usuario::create([
            'is_admin' => true,
            'nome'     => 'Guilherme Mandeli',
            'email'    => 'guimandeli.santos@gmail.com',
            'password' => bcrypt('12345678'),
            'removido' => false,
            'user_id'  => 1,
        ]);
        Usuario::create([

            'nome'     => 'Leonardo Mandeli',
            'email'    => 'leomandeli.santos@gmail.com',
            'password' => bcrypt('12345678'),
            'removido' => false,
            'user_id'  => 1,
        ]);

        Usuario::create([
            'is_admin' => true,
            'nome'     => 'Jessica Balan',
            'email'    => 'jess@gmail.com',
            'password' => bcrypt('12345678'),
            'removido' => false,
            'user_id'  => 2,
        ]);

        CategoriaEntradaSaidaModel::create([
            'id' => -1,
            'nome' => 'Ordens de Serviço',
            'tipo' => TipoEntradaSaida::ENTRADA->value,
            'descricao' => null,
            'removido' => 0,
            'user_id' => null,
        ]);

    }
}
