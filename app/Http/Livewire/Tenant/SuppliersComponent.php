<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\Supplier;

class SuppliersComponent extends CrudComponent
{
    public function mount()
    {
        $this->setup(Supplier::class, [
            'mainKey' => 'name',
            'types' => [
                'name' => ['type' => 'text'],
                'email' => ['type' => 'email'],
                'phone' => ['type' => 'text'],
                'address' => ['type' => 'text'],
                'notes' => ['type' => 'textarea', 'rows' => 3]
            ],
        ]);

    }
}
