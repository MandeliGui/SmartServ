<?php

use App\Enums\FormaPagamentoContratacaoEnum;
use App\Enums\TipoPessoaEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.register')]
class extends Component {
}; ?>

<div>
    <livewire:forms.auth.register/>
</div>
