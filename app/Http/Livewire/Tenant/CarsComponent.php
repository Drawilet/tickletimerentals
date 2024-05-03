<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Tenant\Cars\ScheduleComponent;
use App\Http\Livewire\Tenant\Cars\PhotosComponent;
use App\Http\Livewire\Util\CrudComponent;

use App\Models\Car;
use App\Models\CarPhoto;
use Illuminate\Support\Facades\Auth;

class CarsComponent extends CrudComponent
{
    public $events = ["afterSave"];

    public function mount()
    {
        $this->setup(Car::class, [
            'mainKey' => 'name',
            'types' => [
                'name' => ['type' => 'text'],
                'photos' => [
                    'type' => 'file',
                    'component' => PhotosComponent::class,
                    'hidden' => true,
                    'foreign' => [
                        'model' => CarPhoto::class,
                        'key' => 'car_id',
                        'name' => 'url',
                    ],
                ],
                'description' => ['type' => 'textarea', 'rows' => 4],
                'address' => ['type' => 'text'],
                'city' => ['type' => 'text'],
                'state' => ['type' => 'text'],
                'country' => ['type' => 'text'],
                'schedule' => [
                    'type' => 'array',
                    'component' => ScheduleComponent::class,
                    'hidden' => true,
                ],
                'color' => ['type' => 'color'],
                'notes' => ['type' => 'textarea', 'rules' => 'nullable'],
            ],
            'mobileStyles' => "
                .name {
                    width: 100%;
                    justify-content: center;
                    font-size: 1.2rem;
                    margin-bottom: -8px;
                }

                .photos {
                    width: 100%;
                    justify-content: center;
                    margin-bottom: 10px;
                }

                .description {
                    width: 100%;
                    justify-content: center;
                    font-size: 1rem;
                }

                .address, .city, .state, .country {
                    width: 25%;
                    justify-content: center;
                    font-size: 1rem;
                }

                .color {
                    width: 100%;
                    justify-content: center;
                    font-size: 1rem;
                }

                .color span {
                    margin-top: 10px;
                    width: 100% !important;
                    border-radius: 5px !important;
                }

            ",
            'foreigns' => ['events'],
        ]);
    }

    public function afterSave($car, $data)
    {
        $user = Auth::user();
        $cars = Car::where('tenant_id', $user->tenant_id)->get();

        if ($cars->count() == 1) {
            $user = Auth::user();
            $user->wizard_step = 2;
            $user->save();

            redirect()->route("tenant.cars.show");
        }
    }
}
