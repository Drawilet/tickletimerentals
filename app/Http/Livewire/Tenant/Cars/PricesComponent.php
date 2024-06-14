<?php

namespace App\Http\Livewire\Tenant\Cars;

use Livewire\Component;

class PricesComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $kilometers;
    public $price;
    public $editingId = null;

    public $data = [
        'prices' => [],
    ];

    public function render()
    {
        return view('livewire.tenant.cars.prices-component');
    }

    public function handleData($data)
    {
        if (isset($data['prices'])) {
            $this->data['prices'] = $data['prices'];
        } else {
            $this->data['prices'] = [];
        }
    }

    public function handlepricesChange()
    {
        $this->emitUp('update-data', $this->data);
    }

    public function add()
    {
        $this->validate([
            'kilometers' => 'required',
            'price' => 'required',
        ]);

        $this->data['prices'][] = [
            'kilometers' => $this->kilometers,
            'price' => $this->price,
        ];

        $this->kilometers = '';
        $this->price = '';
        $this->handlepricesChange();
    }
    public function delete($key)
    {
        unset($this->data['prices'][$key]);
        $this->data['prices'] = array_values($this->data['prices']);
        $this->handlepricesChange();
    }


    public function edit($kilometers)
    {
        $this->editingId = $kilometers;
        $price = $this->data['prices'][$kilometers];
        $this->kilometers = $price['kilometers'];
        $this->price = $price['price'];
    }

    public function save()
    {
        $this->data['prices'][$this->editingId] = [
            'kilometers' => $this->kilometers,
            'price' => $this->price,
        ];
        $this->kilometers = '';
        $this->price = '';
        $this->editingId = null;
        $this->handlepricesChange();
    }
}
