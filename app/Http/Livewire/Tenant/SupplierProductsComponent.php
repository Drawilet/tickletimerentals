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
                'iva' => ['type' => 'number'],
                'unit' => [
                    'type' => 'select',
                    'options' => [
                        ['value' => 'kg', 'label' => 'Kg'],
                        ['value' => 'g', 'label' => 'g'],
                        ['value' => 'l', 'label' => 'l'],
                        ['value' => 'ml', 'label' => 'ml'],
                        ['value' => 'pz', 'label' => 'pz'],
                        ['value' => 'm', 'label' => 'm'],
                        ['value' => 'cm', 'label' => 'cm'],
                        ['value' => 'mm', 'label' => 'mm'],
                        ['value' => 'm2', 'label' => 'm2'],
                    ]
                ],
                'notes' => ['type' => 'textarea', 'rules' => 'nullable'],
            ],
        ]);

    }
}
