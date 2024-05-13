<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Tenant\Regions\LocationComponent;
use App\Http\Livewire\Tenant\Regions\RateComponent;
use App\Http\Livewire\Util\CrudComponent;
use App\Models\Region;

class RegionsComponent extends CrudComponent
{
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
            'foreigns' => ['rents'],
        ]);
    }
}
