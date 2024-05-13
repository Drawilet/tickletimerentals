<?php

namespace App\Http\Livewire\Tenant\Regions;

use Khsing\World\Models\Country;
use Khsing\World\Models\Division;
use Livewire\Component;

class CityComponent extends Component
{

    protected $listeners = ['update-data' => 'handleData'];
    public $data = [
        'cities' => [],
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
        return view('livewire.tenant.regions.city-component');
    }

    public function handleCountryChange()
    {
        $country = Country::find($this->filter['country_id']);
        $this->divisions = $country->divisions;
    }

    public function handleDivisionChange()
    {
        $division = Division::find($this->filter['division_id']);
        $this->cities = $division->cities;
    }

    public function addCity($city_id)
    {
        $this->data['cities'][] = [
            'id' => $city_id,
            'name' => $this->cities->where('id', $city_id)->first()->name,
        ];

        $this->handleDataChange();
    }

    public function removeCity($index)
    {
        unset($this->data['cities'][$index]);
        $this->data['cities'] = array_values($this->data['cities']);
        $this->handleDataChange();
    }

    /*<──  ───────    PARENT   ───────  ──>*/
    public function handleData($data)
    {
        if (isset($data['cities'])) {
            $this->data['cities'] = $data['cities'];
        } else {
            $this->data['cities'] = [];
        }
    }

    public function handleDataChange()
    {
        $this->emit('update-data', $this->data);
    }

}
