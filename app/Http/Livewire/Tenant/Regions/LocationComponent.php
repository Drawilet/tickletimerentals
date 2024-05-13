<?php

namespace App\Http\Livewire\Tenant\Regions;

use Khsing\World\Models\Country;
use Khsing\World\Models\Division;
use Livewire\Component;

class LocationComponent extends Component
{

    protected $listeners = ['update-data' => 'handleData'];
    public $data = [
        'locations' => [],
    ];
    public $filter, $initialFilter = [
        'country_id' => 106,
        'division_id' => null,
    ];

    public $countries = [], $divisions = [], $cities = [];

    public function mount()
    {
        $this->filter = $this->initialFilter;
        $this->countries = Country::all();
        $this->handleCountryChange();
    }

    public function render()
    {
        return view('livewire.tenant.regions.location-component');
    }

    public function handleCountryChange()
    {
        $country = Country::find($this->filter['country_id']);
        $this->divisions = $country->divisions;
        $this->data['locations'] = [];
    }


    public function addLocation($location_id)
    {
        $this->data['locations'][] = [
            'id' => $location_id,
            'name' => $this->divisions->where('id', $location_id)->first()->name,
        ];

        $this->handleDataChange();
    }

    public function removeLocation($index)
    {
        unset($this->data['locations'][$index]);
        $this->data['locations'] = array_values($this->data['locations']);
        $this->handleDataChange();
    }

    /*<──  ───────    PARENT   ───────  ──>*/
    public function handleData($data)
    {
        if (isset($data['locations'])) {
            $this->data['locations'] = $data['locations'];
        } else {
            $this->data['locations'] = [];
        }
    }

    public function handleDataChange()
    {
        $this->emit('update-data', $this->data);
    }

}
