<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\Tax;

class TaxesComponent extends CrudComponent
{
    public function mount()
    {
        $this->setup(Tax::class,[
            'mainKey' => 'name',
            'types' => [
                'name' => ['type' => 'text'],
                'rate' => ['type' => 'number', 'step' => '0.01'],
                'code' => ['type' => 'text'],
            ],
        ]);

    }
}
