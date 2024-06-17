<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SuppliersComponent extends CrudComponent
{
    public $events = ["afterSave"];
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
    public function afterSave($sup, $data)
    {
        $user = Auth::user();
        $supp = Supplier::where('tenant_id', $user->tenant_id)->get();

        if ($supp->count() == 1) {
            $user = Auth::user();
            $user->wizard_step++;
            $user->save();

            redirect()->route("tenant.suppliers.show");
        }
    }
}
