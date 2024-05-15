<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Tenant\Regions\LocationComponent;
use App\Http\Livewire\Tenant\Regions\RateComponent;
use App\Http\Livewire\Util\CrudComponent;
use App\Models\Region;

class RegionsComponent extends CrudComponent
{

    public $events = ["afterSave"];
    public function mount()
    {
        $this->setup(Region::class, [
            'mainKey' => 'name',
            'types' => [
                "name" => ['type' => 'text'],
                "locations" => [
                    'type' => 'array',
                    "hidden" => true,
                    "component" => LocationComponent::class,
                ],
                "rate_schedule" => [
                    'type' => 'array',
                    'hidden' => true,
                    'component' => RateComponent::class,

                ],
            ],
            'mobileStyles' => "
                .firstname,
                .lastname {
                     width: 50%;
                     font-size: 1.2rem;
                }

                .firstname, .email {
                    justify-content: flex-end;
                    padding-right: 5px;
                }

                .email,
                .phone {
                     width: 50%;

                }



            ",
            /* 'foreigns' => ['rents'], */
        ]);
    }

    public function afterSave($region, $data)
    {
        $regions = Region::where('id', '!=', $this->data["id"])->get();

        foreach ($regions as $region) {
            $regionLocations = $region->locations;
            $filteredLocations = array_filter($regionLocations, function ($location) use ($data) {
                return !in_array($location['id'], array_column($data['locations'], 'id'));
            });

            if (count($filteredLocations) != count($regionLocations)) {
                $region->locations = $filteredLocations;
                $region->save();
            }
        }
    }
}
