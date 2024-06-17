<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Tenant\Regions\LocationComponent;
use App\Http\Livewire\Tenant\Regions\RateComponent;
use App\Http\Livewire\Util\CrudComponent;
use App\Models\Region;
use Auth;

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
        $regions = Region::where('id', '!=', $region["id"])->get();

        foreach ($regions as $region) {
            $regionLocations = $region->locations;

            $filteredLocations = array_filter($regionLocations, function ($location) use ($data) {
                return !in_array($location, $data['locations']);
            });

            if (count($filteredLocations) != count($regionLocations)) {
                $region->locations = $filteredLocations;
                $region->save();
            }
        }

        $user = Auth::user();
        $regions = Region::where('tenant_id', $user->tenant_id)->get();

        if ($regions->count() == 1) {
            $user = Auth::user();
            $user->wizard_step++;
            $user->save();

            redirect()->route("tenant.regions.show");
        }

    }
}
