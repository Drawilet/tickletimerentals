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


    /*<──  ───────    PARENT   ───────  ──>*/
    public function handleData($data)
    {
        if (isset($data['locations'])) {
            $this->data['locations'] = $data['locations'];
        } else {
            $this->data['locations'] = [];
        }

        $id = $data["id"] ?? null;

        // Occupied locations
        $regions = Region::where('id', '!=', $id)->get();
        $this->occupiedLocations = [];
        foreach ($regions as $region) {
            foreach ($region->locations as $location) {
                $this->occupiedLocations[] = [
                    "id" => $location,
                    "region_name" => $region->name,
                ];
            }
        }
    }

    public function handleDataChange()
    {
        $this->emitUp('update-data', $this->data);
    }

}
