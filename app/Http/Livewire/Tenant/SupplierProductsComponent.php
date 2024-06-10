<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\Supplier;
use App\Models\SupplierProduct;

class SupplierProductsComponent extends CrudComponent
{
    public function mount()
    {
        $this->setup(SupplierProduct::class, [
            'mainKey' => 'name',
            'types' => [
                'photo' => [
                    'type' => 'file',
                    'max' => 1,
                    'accept' => ['image/jpeg', 'image/png'],
                ],
                "supplier_id" => [
                    'type' => 'select',
                    'options' => Supplier::all()->pluck('name', 'id')->map(function ($name, $id) {
                        return ['value' => $id, 'label' => $name];
                    })
                ],
                'sku' => ['type' => 'text'],
                'name' => ['type' => 'text'],
                'description' => ['type' => 'textarea', 'rows' => 4],
                'price' => ['type' => 'number'],
                'notes' => ['type' => 'textarea', 'rules' => 'nullable'],
            ],
        ]);

    }
}
