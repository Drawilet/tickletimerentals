<?php

namespace App\Http\Livewire;

use App\Http\Traits\WithCrudActions;
use App\Models\Car;
use Livewire\Component;

class CarsComponent extends Component
{
    use WithCrudActions;

    protected $listeners = [
        "setFilter" => "setFilter",
    ];

    public $cars, $filteredSpaces;

    public $currentSpace;
    public $modals = [
        "contact" => false,
    ];
    public $filters = [
        "name" => null,
        "city" => null,
        "country" => null,
    ];

    public function mount()
    {
        $this->addCrud(Car::class, ["useItemsKey" => false, "get" => true]);
    }

    public function render()
    {
        $this->filteredSpaces = $this->cars->filter(function ($car) {
            if ($this->filters["country"] != null && strpos(strtolower($car->country), strtolower($this->filters["country"])) === false)
                return false;

            if ($this->filters["city"] != null && strpos(strtolower($car->city), strtolower($this->filters["city"])) === false)
                return false;

            if ($this->filters["name"] != null && strpos(strtolower($car->name), strtolower($this->filters["name"])) === false)
                return false;

            return true;
        });

        return view('livewire.cars-component')->layout("layouts.guest");
    }

    public function Modal($modal, $value, $data = null)
    {
        if ($data) {
            $this->currentSpace = $this->cars->find($data);
        }

        $this->modals[$modal] = $value;
    }

    public function setFilter($key, $value)
    {
        $this->filters[$key] = $value;
    }
}
