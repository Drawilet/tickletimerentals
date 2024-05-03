<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Traits\WithCrudActions;
use App\Models\Car;
use Livewire\Component;

class FilterComponent extends Component
{
    use WithCrudActions;

    public $filters = [
        "cars" => [],
    ];

    public $cars;
    public function mount()
    {
        $this->addCrud(Car::class, ["useItemsKey" => false, "get" => true]);
        $this->filters["cars"] = $this->cars->pluck("id")->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.user-dashboard.filter-component');
    }

    public function toggleSpace($spaceId)
    {
        if ($spaceId == "all") {
            if (count($this->cars) == count($this->filters['cars']))
                $this->filters["cars"] = [];
            else
                $this->filters["cars"] = $this->cars->pluck("id")->toArray();

            $this->emit("update-filters", $this->filters);
            return;
        }

        if (!in_array($spaceId, $this->filters["cars"])) {
            $this->filters["cars"][] = $spaceId;
        } else {
            $this->filters["cars"] = array_diff($this->filters["cars"], [$spaceId]);
        }

        $this->emit("update-filters", $this->filters);
    }
}
