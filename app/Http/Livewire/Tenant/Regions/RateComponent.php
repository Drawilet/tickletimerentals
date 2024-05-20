<?php

namespace App\Http\Livewire\Tenant\Regions;

use Livewire\Component;

class RateComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $usingPrice = true;

    public $days, $price, $discount;
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

    public function handlePriceChange()
    {
        if ($this->price > $this->data['daily_rate']) {
            $this->price = $this->data['daily_rate'];
        } else if ($this->price < 0) {
            $this->price = 0;
        }

        $this->discount = 100 - (($this->price / $this->data['daily_rate']) * 100);
    }

    public function handleDiscountChange()
    {
        if ($this->discount < 0) {
            $this->discount = 0;
        } else if ($this->discount > 100) {
            $this->discount = 100;
        }

        $this->price = $this->data['daily_rate'] - ($this->data['daily_rate'] * ($this->discount / 100));
    }


    public function add()
    {
        $this->validate([
            'days' => 'required|integer|min:2',
            'price' => 'required|integer|min:0|max:' . $this->data['daily_rate'],
            'discount' => 'required|integer|lte:100',
        ]);

        foreach ($this->data['rate_schedule'] as $rate) {
            if ($rate['days'] == $this->days) {
                $this->addError('days', __('region.rate-already-exists'));
                return;
            }
        }

        $this->data['rate_schedule'][] = [
            'days' => $this->days,
            'price' => $this->price,
            'discount' => $this->discount,
        ];

        $this->sortSchedule();

        $this->days = '';
        $this->price = '';
        $this->discount = '';
        $this->handleratesChange();
    }
    public function delete($key)
    {
        unset($this->data['rate_schedule'][$key]);
        $this->data['rate_schedule'] = array_discounts($this->data['rate_schedule']);
        $this->handleratesChange();
    }


    public function edit($key)
    {
        $this->editingId = $key;

        $rate = $this->data['rate_schedule'][$key];
        $this->days = $rate['days'];
        $this->price = $rate['price'];
        $this->discount = $rate['discount'];
    }

    public function save()
    {
        $this->data['rate_schedule'][$this->editingId] = [
            'days' => $this->days,
            'price' => $this->price,
            'discount' => $this->discount,
        ];

        $this->days = '';
        $this->price = '';
        $this->discount = '';
        $this->editingId = null;

        $this->sortSchedule();

        $this->handleratesChange();
    }

    public function sortSchedule()
    {
        usort($this->data['rate_schedule'], function ($a, $b) {
            return $a['days'] <=> $b['days'];
        });
    }


}
