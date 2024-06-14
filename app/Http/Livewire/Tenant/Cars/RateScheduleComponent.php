<?php

namespace App\Http\Livewire\Tenant\Cars;

use App\Models\Region;
use Livewire\Component;

class RateScheduleComponent extends Component
{
    protected $listeners = ['update-data' => 'handleData'];

    public $dailyRate = 0;

    public $days, $price, $discount, $currentRegion = 1;

    public $data = [
        'rate_schedule' => [],
    ];

    public $regions;

    public function mount()
    {
        $this->regions = Region::select('id', 'name')->get();
        foreach ($this->regions as $region) {
            $this->data['rate_schedule']['region-' . $region->id] = [
                [
                    'days' => '1',
                    'price' => '1000',
                    'discount' => '0',
                ]
            ];
        }
    }

    public function render()
    {
        $this->dailyRate = $this->data['rate_schedule']['region-' . $this->currentRegion][0]['price'] ?? 0;

        return view('livewire.tenant.cars.rate-schedule-component');
    }

    public function handleData($data)
    {
        if (isset($data['rate_schedule'])) {
            $this->data['rate_schedule'] = $data['rate_schedule'];
        } else {
            $this->data['rate_schedule'] = [];
        }

    }

    public function handleratesChange()
    {
        $this->emitUp('update-data', $this->data);
    }

    public function handlePriceChange($index = null)
    {
        if ($index == null) {
            if ($this->price > $this->dailyRate) {
                $this->price = $this->dailyRate;
            } else if ($this->price < 0) {
                return;
            }

            $this->discount = 100 - (($this->price / $this->dailyRate) * 100);
        } else {
            if ($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['price'] > $this->dailyRate) {
                $this->data['rate_schedule']['region-' . $this->currentRegion][$index]['price'] = $this->dailyRate;
            } else if ($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['price'] < 0) {
                return;
            }

            $this->data['rate_schedule']['region-' . $this->currentRegion][$index]['discount'] = 100 - (($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['price'] / $this->dailyRate) * 100);
        }
    }

    public function handleDiscountChange($index = null)
    {
        if ($index == null) {
            if ($this->discount < 0) {
                return;
            } else if ($this->discount > 100) {
                $this->discount = 100;
            }

            $this->price = $this->dailyRate - ($this->dailyRate * ($this->discount / 100));
        } else {
            if ($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['discount'] < 0) {
                return;
            } else if ($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['discount'] > 100) {
                $this->data['rate_schedule']['region-' . $this->currentRegion][$index]['discount'] = 100;
            }

            $this->data['rate_schedule']['region-' . $this->currentRegion][$index]['price'] = $this->dailyRate - ($this->dailyRate * ($this->data['rate_schedule']['region-' . $this->currentRegion][$index]['discount'] / 100));
        }

    }


    public function add()
    {
        $this->validate([
            'days' => 'required|integer|min:2',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|lte:100',
        ]);

        foreach ($this->data['rate_schedule']['region-' . $this->currentRegion] as $rate) {
            if ($rate['days'] == $this->days) {
                $this->addError('days', __('region.rate-already-exists'));
                return;
            }
        }

        $this->data['rate_schedule']['region-' . $this->currentRegion][] = [
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
    public function remove($key)
    {
        unset($this->data['rate_schedule']['region-' . $this->currentRegion][$key]);
        $this->handleratesChange();
    }


    public function sortSchedule()
    {
        usort($this->data['rate_schedule']['region-' . $this->currentRegion], function ($a, $b) {
            return $a['days'] <=> $b['days'];
        });
    }

}
