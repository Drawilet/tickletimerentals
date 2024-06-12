<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\ExpenseCategory;
use App\Models\Supplier;

class ExpenseCategoriesComponent extends CrudComponent
{
    public function mount()
    {
        $this->setup(ExpenseCategory::class, [
            'mainKey' => 'name',
            'types' => [
                'name' => ['type' => 'text'],
                'description' => ['type' => 'textarea', 'rows' => 4, "rules" => "nullable"],
                'iva' => ['type' => 'number', 'step' => '0.01', 'min' => 0, 'max' => 100, 'rules' => 'required'],
            ],
        ]);

    }
}
