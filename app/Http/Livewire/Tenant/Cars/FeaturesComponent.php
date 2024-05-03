<?php

namespace App\Http\Livewire\Tenant\Cars;

use Livewire\Component;

class FeaturesComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $data = [
        'features' => [],
    ];

    public function render()
    {
        return view('livewire.tenant.cars.features-component');
    }

    public function handleData($data)
    {
        if (isset($data['features'])) {
            $this->data['features'] = $data['features'];
        } else {
            $this->data['features'] = [];
        }
    }

    public function handlefeaturesChange()
    {
        $this->emit('update-data', $this->data);
    }

}
