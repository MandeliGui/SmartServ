<?php

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Livewire\Forms\TecnicoForm;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public TecnicoForm $form;
    public ?Persistence $persistence = Persistence::CREATE;
    public $calendario = null;

    public function updatedCalendar()
    {
        ds($this->calendario);
    }

    public function save(): void
    {
        dd($this->calendario);
        if ($this->persistence == Persistence::UPDATE) {

            $cliente = $this->form->update();

            $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::UPDATE->value, cliente: $cliente);
        } else {

            $cliente = $this->form->create();


            $this->dispatch(TecnicoForm::EVENT_PERSISTED, persistence: Persistence::CREATE->value, cliente: $cliente);
        }

    }

    public function mount()
    {
        $this->id = request()->route('id') ?? null;

        if ($this->id) {

            $this->persistence = Persistence::UPDATE;
            $this->form->setTecnico($this->id);

        }
    }


};
?>

<div x-data>
    <form wire:submit="save">

        <flux:calendar value="2025-04-25" wire:model.live="calendario"/>
        <flux:button variant="primary">Save</flux:button>
    </form>

</div>

