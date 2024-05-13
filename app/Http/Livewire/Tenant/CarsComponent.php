<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Tenant\Cars\FeaturesComponent;
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
                'color' => ['type' => 'color'],
                'plate_number' => ['type' => 'text'],
                'brand' => ['type' => 'text'],
                'model' => ['type' => 'text'],
                'year' => ['type' => 'number'],
                'fuel_type' => [
                    'type' => 'select',
                    'options' =>
                        [
                            ["value" => "gasoline", "label" => __("car.fuel.gasoline")],
                            ["value" => "diesel", "label" => __("car.fuel.diesel")],
                            ["value" => "electric", "label" => __("car.fuel.electric")],
                            ["value" => "hybrid", "label" => __("car.fuel.hybrid")],
                            ["value" => "lpg", "label" => __("car.fuel.lpg")],
                            ["value" => "cng", "label" => __("car.fuel.cng")],
                            ["value" => "bioethanol", "label" => __("car.fuel.bioethanol")],
                            ["value" => "hydrogen", "label" => __("car.fuel.hydrogen")],
                            ["value" => "other", "label" => __("car.fuel.other")],
                        ]
                ],
                'transmission' => [
                    'type' => 'select',
                    'options' =>
                        [
                            ["value" => "manual", "label" => __("car.transmission_type.manual")],
                            ["value" => "automatic", "label" => __("car.transmission_type.automatic")],
                            ["value" => "semiautomatic", "label" => __("car.transmission_type.semi-automatic")],
                            ["value" => "cv", "label" => __("car.transmission_type.cv")],
                            ["value" => "other", "label" => __("car.transmission_type.other")],
                        ]
                ],
                'engine' => ['type' => 'text'],
                'seats' => ['type' => 'number'],
                'doors' => ['type' => 'number'],
                "features" => [
                    "type" => "array",
                    "hidden" => true,
                    "component" => FeaturesComponent::class,
                ],
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
            'foreigns' => ['rents'],
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
