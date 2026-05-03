<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SmartServ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-stone-950 text-stone-100 antialiased">
<div class="relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.26),_transparent_35%),radial-gradient(circle_at_80%_20%,_rgba(234,88,12,0.18),_transparent_28%),linear-gradient(180deg,_#1c1917_0%,_#0c0a09_100%)]"></div>
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-orange-400/60 to-transparent"></div>

    <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-8 lg:px-10">
        <header class="flex items-center justify-between">
            <a href="/" class="flex items-center" aria-label="SmartServ">
                <span class="text-3xl font-semibold tracking-tight text-stone-50 sm:text-[2rem]">
                    <span class="text-orange-500">S</span>mart<span class="text-orange-500">Serv</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="https://wa.me/5543998442622"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="hidden rounded-full border border-white/10 px-4 py-2 text-sm font-medium text-stone-300 transition hover:border-white/20 hover:text-white sm:inline-flex">
                    Entrar em contato
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex rounded-full bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-400">
                    Entrar
                </a>
            </div>
        </header>

        <main class="flex flex-1 py-14 lg:py-20">
            <div class="w-full">
                <section class="max-w-4xl">
                    <div class="inline-flex rounded-full border border-orange-400/25 bg-orange-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                        Gestão de serviços e operação técnica
                    </div>

                    <h1 class="mt-6 max-w-4xl text-5xl font-semibold leading-none text-white sm:text-6xl lg:text-7xl">
                        Controle atendimentos, materiais, contratos e financeiro em um só sistema.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                        O SmartServ organiza o dia a dia da operação: cadastro de clientes, ordens de serviço,
                        lançamentos financeiros, bancos, materiais e pagamentos sem planilhas espalhadas.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-full bg-orange-500 px-6 py-3 text-base font-semibold text-white transition hover:bg-orange-400">
                            Acessar sistema
                        </a>
                        <a href="https://wa.me/5543998442622"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center justify-center rounded-full border border-white/10 px-6 py-3 text-base font-semibold text-stone-200 transition hover:border-white/20 hover:text-white">
                            Entrar em contato
                        </a>
                    </div>

                    <div class="mt-8 max-w-xl rounded-[1.75rem] border border-orange-400/30 bg-gradient-to-r from-orange-500/20 to-orange-400/10 p-5 shadow-lg shadow-orange-950/20">
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-200">Oferta promocional</div>
                        <div class="mt-3 flex items-end gap-2">
                            <span class="text-4xl font-semibold leading-none text-white sm:text-5xl">R$ 75</span>
                            <span class="pb-1 text-sm font-medium text-orange-100/90 sm:text-base">por mês</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-orange-50/85">
                            Valor promocional para começar a organizar operação, ordens de serviço, materiais e financeiro em um único sistema.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-white/8 bg-white/5 p-4 backdrop-blur">
                            <div class="text-sm font-semibold text-white">Ordens de serviço</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Acompanhe abertura, execução, materiais e fechamento.</div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-white/5 p-4 backdrop-blur">
                            <div class="text-sm font-semibold text-white">Financeiro</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Entradas, saídas, bancos, categorias e baixa de pagamentos.</div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-white/5 p-4 backdrop-blur">
                            <div class="text-sm font-semibold text-white">Cadastros centrais</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Clientes, fornecedores, serviços, técnicos e contratos.</div>
                        </div>
                    </div>
                </section>

                <section class="mt-20 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[2rem] border border-white/8 bg-white/[0.04] p-6 backdrop-blur">
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">Para quem é</div>
                        <h2 class="mt-4 text-3xl font-semibold text-white">Feito para empresas de serviço que precisam organizar operação e financeiro.</h2>
                        <p class="mt-4 max-w-2xl text-base leading-8 text-stone-300">
                            O SmartServ faz sentido para negócios que atendem clientes de forma recorrente,
                            trabalham com equipe técnica, usam materiais no atendimento e precisam manter
                            contratos, cobranças e pagamentos sob controle.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/8 bg-black/20 p-5">
                            <div class="text-sm font-semibold text-white">Assistência técnica</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Controle de chamados, visitas, peças e andamento dos serviços.</div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-black/20 p-5">
                            <div class="text-sm font-semibold text-white">Instalação e manutenção</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Gestão de equipes em campo, materiais aplicados e histórico dos atendimentos.</div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-black/20 p-5">
                            <div class="text-sm font-semibold text-white">Operações com contrato</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Acompanhamento de contratos ativos, serviços recorrentes e rotina mensal.</div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-black/20 p-5">
                            <div class="text-sm font-semibold text-white">Pequenas e médias equipes</div>
                            <div class="mt-2 text-sm leading-6 text-stone-300">Mais visibilidade para quem já cresceu além do controle por planilha e WhatsApp.</div>
                        </div>
                    </div>
                </section>

                <section class="mt-16">
                    <div class="max-w-3xl">
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">Onde ajuda na prática</div>
                        <h2 class="mt-4 text-3xl font-semibold text-white">Menos retrabalho operacional. Mais clareza no que está aberto, em execução e já pago.</h2>
                    </div>

                    <div class="mt-8 grid gap-4 lg:grid-cols-3">
                        <div class="rounded-3xl border border-orange-500/20 bg-orange-500/10 p-6">
                            <div class="text-sm font-semibold text-white">Antes do atendimento</div>
                            <div class="mt-3 text-sm leading-7 text-orange-100/85">
                                Organize cliente, contrato, técnico, serviço e materiais antes de sair executando.
                            </div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-white/[0.04] p-6">
                            <div class="text-sm font-semibold text-white">Durante a operação</div>
                            <div class="mt-3 text-sm leading-7 text-stone-300">
                                Registre ordens de serviço, acompanhe o uso de materiais e mantenha o histórico centralizado.
                            </div>
                        </div>
                        <div class="rounded-3xl border border-white/8 bg-white/[0.04] p-6">
                            <div class="text-sm font-semibold text-white">Depois da execução</div>
                            <div class="mt-3 text-sm leading-7 text-stone-300">
                                Lance entradas e saídas, faça baixas, acompanhe bancos e evite perder o controle do financeiro.
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="flex flex-col gap-3 border-t border-white/8 pt-6 text-sm text-stone-400 sm:flex-row sm:items-center sm:justify-between">
            <div>SmartServ</div>
            <div>Sistema para operação, atendimento e controle financeiro.</div>
        </footer>
    </div>
</div>

@fluxScripts
</body>
</html>
