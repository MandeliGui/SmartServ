<?php

declare(strict_types=1);

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

//        User::create([
//            'name'          => 'Guilherme Mandeli',
//            'razao_social'  => 'Guilherme Mandeli',
//            'nome_fantasia' => 'Guilherme Mandeli',
//            'email'         => 'guimandeli.santos@gmail.com',
//            'password'      => bcrypt('12345678'),
//            'cpf_cnpj'      => '12706497955',
//            'telefone'      => '43998442622',
//        ]);
//
//        User::create([
//            'name'          => 'Jessica Balan',
//            'razao_social'  => 'Jessica Balan',
//            'nome_fantasia' => 'Jessica Balan',
//            'email'         => 'jess@gmail.com',
//            'password'      => bcrypt('12345678'),
//            'cpf_cnpj'      => '11441384936',
//            'telefone'      => '4396536081',
//        ]);

        User::create([
            'name'          => 'Gelson Emilio dos Santos',
            'razao_social'  => 'Hidrolondrina Encanamentos e Serviços Hidráulicos Ltda',
            'nome_fantasia' => 'Hidrolondrina',
            'email'         => 'gelson.emilio@gmail.com',
            'password'      => bcrypt('12345678'),
            'cpf_cnpj'      => '63378287000108',
            'telefone'      => '43998210135',
        ]);


//        Usuario::create([
//            'is_admin' => true,
//            'nome'     => 'Guilherme Mandeli',
//            'email'    => 'guimandeli.santos@gmail.com',
//            'password' => bcrypt('12345678'),
//            'removido' => false,
//            'user_id'  => 1,
//        ]);
//        Usuario::create([
//
//            'nome'     => 'Leonardo Mandeli',
//            'email'    => 'leomandeli.santos@gmail.com',
//            'password' => bcrypt('12345678'),
//            'removido' => false,
//            'user_id'  => 1,
//        ]);
//
//        Usuario::create([
//            'is_admin' => true,
//            'nome'     => 'Jessica Balan',
//            'email'    => 'jess@gmail.com',
//            'password' => bcrypt('12345678'),
//            'removido' => false,
//            'user_id'  => 2,
//        ]);

        Usuario::create([
            'is_admin' => true,
            'nome'     => 'Gelson Emilio dos Santos',
            'email'    => 'gelson.emilio@gmail.com',
            'password' => bcrypt('12345678'),
            'removido' => false,
            'user_id'  => 3,
        ]);


//
//        CategoriaEntradaSaidaModel::create([
//            'id'        => -1,
//            'nome'      => 'Ordens de Serviço',
//            'tipo'      => TipoEntradaSaida::ENTRADA->value,
//            'descricao' => null,
//            'removido'  => 0,
//            'user_id'   => null,
//        ]);

    }
}
