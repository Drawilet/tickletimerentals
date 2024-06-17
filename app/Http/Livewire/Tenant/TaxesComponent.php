<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;

use App\Models\Tax;
use Illuminate\Support\Facades\Auth;

class TaxesComponent extends CrudComponent
{
    public $events = ["afterSave"];
    public function mount()
    {
        $this->setup(Tax::class, [
            'mainKey' => 'name',
            'types' => [
                'name' => ['type' => 'text'],
                'code' => ['type' => 'text'],
                'rate' => ['type' => 'number', 'step' => '0.01'],
            ],
        ]);
    }
    public function afterSave($tax, $data)
    {
        $user = Auth::user();
        $count = Tax::count();

        if ($count == 1) {
            $user->wizard_step++;
            $user->save();

            return redirect()->route("tenant.taxes.show");
        }
    }
}
