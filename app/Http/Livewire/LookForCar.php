<?php

namespace App\Http\Livewire;

use App\Models\Car;
use App\Models\Region;
use Carbon\Carbon;
use Livewire\Component;

class LookForCar extends Component
{
    public $modals = [
        'search' => false,
    ];

    public $regions, $cars;

    public $filters, $initialFilters = [
        'region_id' => null,
        'start_date' => null,
        'end_date' => null,
    ];

    public $busyCars = [], $notAvailableCars = [];


    public function mount()
    {
        $this->filters = $this->initialFilters;
        $this->regions = Region::all();
        $this->cars = Car::select('id', 'name')->get();
    }

    public function render()
    {
        // region filter
        /*        $region = $this->regions->find($this->rent["region_id"]);
        $start_date = Carbon::parse($this->rent["start_date"]);
        $end_date = Carbon::parse($this->rent["end_date"]);

        $this->avaiableCars = $this->cars->filter(function ($car) use ($start_date, $end_date, $region) {
            return $car->isAvailable($start_date, $end_date) && in_array('' . $region->id, $car->getRegions());
        }); */
        if ($this->filters['region_id']) {
            $this->notAvailableCars = $this->cars->map(function ($car) {
                return in_array($this->filters['region_id'], $car->getRegions()) ? null : $car->id;
            })->filter()->toArray();
        }



        if ($this->filters['start_date'] && $this->filters['end_date']) {
            $start_date = Carbon::parse($this->filters['start_date']);
            $end_date = Carbon::parse($this->filters['end_date']);

            $this->busyCars = $this->cars->map(function ($car) use ($start_date, $end_date) {
                return $car->isAvailable($start_date, $end_date) ? null : $car->id;
            })->filter()->toArray();
        }

        return view('livewire.look-for-car');
    }
}
