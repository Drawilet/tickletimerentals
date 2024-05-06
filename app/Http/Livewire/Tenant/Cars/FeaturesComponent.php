<?php

namespace App\Http\Livewire\Tenant\Cars;

use Livewire\Component;

class FeaturesComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $name;
    public $value;
    public $editingId = null;

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

    public function add()
    {
        $this->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        $this->data['features'][] = [
            'name' => $this->name,
            'value' => $this->value,
        ];

        $this->name = '';
        $this->value = '';
        $this->handlefeaturesChange();
    }
    public function delete($key)
    {
        unset($this->data['features'][$key]);
        $this->data['features'] = array_values($this->data['features']);
        $this->handlefeaturesChange();
    }


    public function edit($name)
    {
        $this->editingId = $name;
        $feature = $this->data['features'][$name];
        $this->name = $feature['name'];
        $this->value = $feature['value'];
    }

    public function save()
    {
        $this->data['features'][$this->editingId] = [
            'name' => $this->name,
            'value' => $this->value,
        ];
        $this->name = '';
        $this->value = '';
        $this->editingId = null;
        $this->handlefeaturesChange();
    }
}

