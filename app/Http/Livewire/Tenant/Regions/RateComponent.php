<?php

namespace App\Http\Livewire\Tenant\Regions;

use Livewire\Component;

class RateComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $usingPrice = true;

    public $min, $max, $value;
    public $editingId = null;

    public $data = [
        'rate_schedule' => [],
        'daily_rate' => null,
    ];

    public function render()
    {
        return view('livewire.tenant.regions.rate-component');
    }

    public function handleData($data)
    {
        if (isset($data['rate_schedule'])) {
            $this->data['rate_schedule'] = $data['rate_schedule'];
        } else {
            $this->data['rate_schedule'] = [];
        }

        if (isset($data['daily_rate'])) {
            $this->data['daily_rate'] = $data['daily_rate'];
        } else {
            $this->data['daily_rate'] = null;
        }

        if ($this->data['daily_rate'] == null) {
            $this->usingPrice = true;
        }
    }

    public function handleratesChange()
    {
        $this->emitUp('update-data', $this->data);
    }


    public function add()
    {
        $this->validate([
            'min' => 'required',
            'max' => 'required',
            'value' => 'required',
        ]);

        $this->data['rate_schedule'][] = [
            'min' => $this->min,
            'max' => $this->max,
            'value' => $this->value,
        ];

        $this->min = '';
        $this->max = '';
        $this->value = '';
        $this->handleratesChange();
    }
    public function delete($key)
    {
        unset($this->data['rate_schedule'][$key]);
        $this->data['rate_schedule'] = array_values($this->data['rate_schedule']);
        $this->handleratesChange();
    }


    public function edit($key)
    {
        $this->editingId = $key;

        $rate = $this->data['rate_schedule'][$key];
        $this->min = $rate['min'];
        $this->max = $rate['max'];
        $this->value = $rate['value'];
    }

    public function save()
    {
        $this->data['rate_schedule'][$this->editingId] = [
            'min' => $this->min,
            'max' => $this->max,
            'value' => $this->value,
        ];

        $this->min = '';
        $this->max = '';
        $this->value = '';
        $this->editingId = null;

        $this->handleratesChange();
    }


}
