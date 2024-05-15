<?php

namespace App\Http\Livewire\Tenant\Regions;

use App\Models\Region;
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

    public $countries = [],
    $divisions = [],
    $cities = [],
    $occupiedLocations = [];


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

    public function toggleLocation($location_id)
    {
        if (in_array($location_id, array_column($this->data['locations'], 'id'))) {
            $this->removeLocation(array_search($location_id, array_column($this->data['locations'], 'id')));
        } else {
            $this->addLocation($location_id);
        }

        $this->handleDataChange();
    }

    public function addLocation($location_id)
    {
        if (in_array($location_id, array_column($this->data['locations'], 'id'))) {
            return;
        }

        $this->data['locations'][] = [
            'id' => $location_id,
            'name' => $this->divisions->where('id', $location_id)->first()->name,
        ];

    }

    public function removeLocation($index)
    {
        unset($this->data['locations'][$index]);
        $this->data['locations'] = array_values($this->data['locations']);
    }

    /*<──  ───────    PARENT   ───────  ──>*/
    public function handleData($data)
    {
        if (isset($data['locations'])) {
            $this->data['locations'] = $data['locations'];
        } else {
            $this->data['locations'] = [];
        }

        $this->data["id"] = $data["id"];

        // Occupied locations
        $regions = Region::where('id', '!=', $this->data["id"])->get();
        $this->occupiedLocations = [];
        foreach ($regions as $region) {
            foreach ($region->locations as $location) {
                $this->occupiedLocations[] = [
                    "id" => $location["id"],
                    "region_name" => $region->name,
                ];
            }
        }
    }

    public function handleDataChange()
    {
        $this->emit('update-data', $this->data);
    }

}
