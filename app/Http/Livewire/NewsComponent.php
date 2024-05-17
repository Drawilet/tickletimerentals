<?php

namespace App\Http\Livewire;

use App\Http\Traits\WithCrudActions;
use App\Models\Rent;
use App\Models\RentProduct;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;

class NewsComponent extends Component
{
    use WithCrudActions;
    protected $listeners = [
        "toggleNews" => "toggleNews"
    ];

    public $modals = [
        "news" => false,
    ];

    public $rents, $filteredRents, $products, $offON;

    public function mount()
    {
        $this->addCrud(Rent::class, ["useItemsKey" => false, "get" => false, "afterUpdate" => "getProducts"]);
        $this->addCrud(Product::class, ["useItemsKey" => false, "get" => true]);

        $this->rents = Rent::whereBetween("start_date", [
            Carbon::now()->format("Y-m-d"),
            Carbon::now()->addDays(1)->format("Y-m-d")
        ])->get();

        if ($this->rents->isEmpty()) {
            $this->emit('toggleNews', false);
        }
    }

    public function render()
    {
        $this->filteredRents =
            $this->rents->filter(function ($rent) {
                return count($rent->payments) > 0;
            });

        if ($this->filteredRents->isEmpty()) {
            $this->modals["news"] = false;
            $this->offON = false;
        } else {
            $this->offON = true;
        }

        return view('livewire.news-component');
    }

    public function getTotal($rent)
    {
        $total = $rent["price"] ?? 0;
        foreach ($rent["products"] as $data) {
            $total += $this->products->find($data["product_id"])->price * $data["quantity"];
        }
        return $total;
    }

    public function getRemaining($id)
    {
        $rent = $this->rents->find($id);
        $remaining = $this->getTotal($rent);
        foreach ($rent["payments"] as $payment) {
            $remaining -= $payment["amount"];
        }
        return $remaining;
    }

    public function getProducts()
    {
        if ($this->rents->isEmpty())
            return;
        if ($this->products->isEmpty())
            return;

        $rent = $this->rents->last();
        if (!$rent)
            return;

        $products = RentProduct::where("rent_id", $rent->id)->get();
        $rent->products = $products;

        $this->rents->pop();
        $this->rents->push($rent);
    }


    public function toggleNews($value)
    {
        $this->modals["news"] = $value;
    }


    public function openRent($id)
    {
        $this->toggleNews(false);
        $this->emit("Modal", "save", true, ["id" => $id]);
    }
}
