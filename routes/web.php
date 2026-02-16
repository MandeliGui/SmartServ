<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Api\OrdemServicoController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', 'login');

Route::view('dashboard', 'dashboard')
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::view('profile', 'profile')
     ->middleware(['auth'])
     ->name('profile');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::prefix('clientes')->group(function (): void {
        Volt::route('/', 'pages.tenant.clientes.search')
            ->name('clientes');

        Volt::route('/novo', 'pages.tenant.clientes.create')
            ->name('clientes.novo');

        Volt::route('/editar/{id}', 'pages.tenant.clientes.create')
            ->name('clientes.editar');
    });

    Route::prefix('grupos-clientes')->group(function (): void {
        Volt::route('/', 'pages.tenant.grupo-cliente.search')
            ->name('grupo-cliente');

        Volt::route('/novo', 'pages.tenant.grupo-cliente.create')
            ->name('grupo-cliente.novo');

        Volt::route('/editar/{id}', 'pages.tenant.grupo-cliente.create')
            ->name('grupo-cliente.editar');
    });

    Route::prefix('servicos')->group(function (): void {
        Volt::route('/', 'pages.tenant.servicos.search')
            ->name('servicos');
//
//        Volt::route('/novo', 'pages.tenant.servicos.create')
//            ->name('servicos.novo');
//
//        Volt::route('/editar/{id}', 'pages.tenant.servicos.create')
//            ->name('servicos.editar');
    });

    Route::prefix('tecnico')->group(function (): void {
        Volt::route('/', 'pages.tenant.tecnico.search')
            ->name('tecnico');

        Volt::route('/novo', 'pages.tenant.tecnico.create')
            ->name('tecnico.novo');

        Volt::route('/editar/{id}', 'pages.tenant.tecnico.create')
            ->name('tecnico.editar');
    });

    Route::prefix('usuarios')->group(function (): void {
        Volt::route('/', 'pages.tenant.usuarios.search')
            ->name('usuarios');

        Volt::route('/novo', 'pages.tenant.usuarios.create')
            ->name('usuarios.novo');

        Volt::route('/editar/{id}', 'pages.tenant.usuarios.create')
            ->name('usuarios.editar');
    });

    Route::prefix('formas-pagamento')->group(function (): void {
        Volt::route('/', 'pages.tenant.forma-pagamento.search')
            ->name('formas-pagamento');

    });

    Route::prefix('materiais')->group(function (): void {
        Volt::route('/', 'pages.tenant.materiais.search')
            ->name('materiais');


    });

    Route::prefix('atendentes')->group(function (): void {
        Volt::route('/', 'pages.tenant.atendentes.search')
            ->name('atendentes');

        Volt::route('/novo', 'pages.tenant.atendentes.create')
            ->name('atendentes.novo');

        Volt::route('/editar/{id}', 'pages.tenant.atendentes.create')
            ->name('atendentes.editar');
    });

    Route::prefix('ordem-servico')->group(function (): void {
        Volt::route('/', 'pages.tenant.ordem-servico.search')
            ->name('ordem-servico');

        Volt::route('/novo', 'pages.tenant.ordem-servico.create')
            ->name('ordem-servico.novo');

        Volt::route('/editar/{id}', 'pages.tenant.ordem-servico.create')
            ->name('ordem-servico.editar');
    });

    Route::prefix('contratos')->group(function (): void {
        Volt::route('/', 'pages.tenant.contratos.search')
            ->name('contratos');

        Volt::route('/novo', 'pages.tenant.contratos.create')
            ->name('contratos.novo');

        Volt::route('/editar/{id}', 'pages.tenant.contratos.create')
            ->name('contratos.editar');

        Volt::route('/mes', 'pages.tenant.contratos.contratos-do-mes')
            ->name('contratos.mes');

    });

    Route::prefix('categoria-entrada-saida')->group(function (): void {
        Volt::route('/', 'pages.tenant.categoria-entrada-saida.search')
            ->name('categoria-entrada-saida');

    });

    Route::prefix('entradas-saidas')->group(function (): void {
        Volt::route('/', 'pages.tenant.entrada-saida.search')
            ->name('entradas-saidas');

    });

    Route::prefix('bancos')->group(function (): void {
        Volt::route('/', 'pages.tenant.bancos.search')
            ->name('bancos');

    });

    Route::prefix('fornecedores')->group(function (): void {
        ;
        Volt::route('/', 'pages.tenant.fornecedores.search')
            ->name('fornecedores');

        Volt::route('/novo', 'pages.tenant.fornecedores.create')
            ->name('fornecedores.novo');

        Volt::route('/editar/{id}', 'pages.tenant.fornecedores.create')
            ->name('fornecedores.editar');
    });

    Route::get('/ordem-servico/{id}/pdf', [OrdemServicoController::class, 'gerarPdf'])->name('ordem-servico.pdf');
    Route::get('/ordem_servico/{id}/pdf', [OrdemServicoController::class, 'gerarPdfSemValor'])->name('ordem_servico.pdf');
});


require __DIR__ . '/auth.php';
