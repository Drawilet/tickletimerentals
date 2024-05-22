<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Traits\WithCrudActions;
use App\Http\Traits\WithValidations;
use App\Models\Customer;
use App\Models\Region;
use App\Models\Rent;
use App\Models\RentPayment;
use App\Models\Product;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class UserDashboardComponent extends Component
{
    use WithCrudActions, WithValidations;
    protected $listeners = [
        "Modal" => "Modal",
    ];

    public $modals = [
        "save" => false,
        "delete" => false,
        "addProduct" => false,
        "newCustomer" => false,
        "payments" => false,
    ];
    public $rent, $initialRent = [
        "customer_id" => null,
        "name" => null,
        "car_id" => null,
        "region_id" => null,

        "start_date" => null,
        "end_date" => null,

        "notes" => null,

        "products" => [],
    ];

    public $filters, $initialFilters = [
        "product_name" => null,
    ];

    public $payment, $initialRentPayment = [
        "user_id" => null,
        "rent_id" => null,
        "amount" => null,
        "notes" => null,
    ];

    public $customer, $initialCustomer = [
        "firstname" => null,
        "lastname" => null,
        "email" => null,
        "phone" => null,
        "address" => null,
        "notes" => null,
    ];

    public $products, $filteredProducts, $customers, $cars, $regions, $payments, $rents;

    public $currentSpace;
    public $searchTerm;
    public $SelectCustomer;

    public $customerName = '';

    public $skip_customer = 0;

    public $CUSTOMER_PER_PAGE = 10;
    public $CAN_LOAD_MORE = true;

    public function mount()
    {
        $this->addCrud(Rent::class, ["useItemsKey" => false, "get" => true, "afterUpdate" => "updateRents"]);
        $this->addCrud(RentPayment::class, ["useItemsKey" => false, "get" => true]);
        $this->addCrud(Customer::class, ["useItemsKey" => false, "get" => true]);
        $this->addCrud(Car::class, ["useItemsKey" => false, "get" => true]);
        $this->addCrud(Product::class, ["useItemsKey" => false, "get" => true]);
        $this->addCrud(Region::class, ["useItemsKey" => false, "get" => true]);

        $this->rent = $this->initialRent;
        $this->payment = $this->initialRentPayment;
        $this->filters = $this->initialFilters;

        $this->customers = Customer::take($this->CUSTOMER_PER_PAGE)->get();
        $this->skip_customer = $this->CUSTOMER_PER_PAGE;

    }
    public function SetCustomer($id)
    {
        $this->SelectCustomer = Customer::find($id);
        $this->searchTerm = $this->SelectCustomer->firstname . ' ' . $this->SelectCustomer->lastname;
        $this->rent['customer_id'] = $id;

    }

    public function loadMore()
    {
        $NewCustomers = Customer::when($this->searchTerm != '', function ($query) {
            return $query->where('firstname', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('lastname', 'like', '%' . $this->searchTerm . '%');
        })
            ->skip($this->skip_customer)
            ->take($this->CUSTOMER_PER_PAGE)
            ->get();

        $this->customers = $this->customers->merge($NewCustomers);

        $this->skip_customer += $this->CUSTOMER_PER_PAGE;

        if ($NewCustomers->count() < $this->CUSTOMER_PER_PAGE) {
            $this->CUSTOMER_PER_PAGE = false;
        }

    }
    public function filterUpdated()
    {
        $this->skip_customer = 0;
        $this->customers = new Collection();
        $this->CUSTOMER_PER_PAGE = 10;
        $this->loadMore();
    }

    public function render()
    {
        $this->filteredProducts = $this->products->filter(function ($product) {
            if ($this->filters["product_name"] && !str_contains(strtolower($product->name), strtolower($this->filters["product_name"])))
                return false;
            return true;
        });

        $this->currentSpace = $this->cars->find($this->rent["car_id"]) ?? null;

        $this->CAN_LOAD_MORE = $this->customers->count() > $this->skip_customer;
        return view('livewire.dashboard.user-dashboard-component');
    }

    public function Modal($name, $value, $data = null)
    {
        switch ($name) {
            case 'save':
                if ($value === true) {
                    $this->rent = $this->initialRent;
                    $this->searchTerm = '';
                } else
                    $this->modals["payments"] = false;

                if ($data) {
                    if (gettype($data) != "array" && array_keys($data->toArray()) > 2) {
                        $this->rent = array_merge(
                            $this->rent,
                            $data->load("products", "payments", "customer")->toArray()
                        );
                    } else if (isset($data["id"])) {
                        $rent = Rent::find($data["id"]);
                        if ($rent)
                            $this->rent = array_merge(
                                $this->rent,
                                $rent->load("products", "payments", "customer")->toArray()
                            );
                    } else
                        $this->rent = array_merge($this->rent, $data);
                }

                if (isset($rent) && isset($rent["customer"])) {
                    $this->searchTerm = $rent["customer"]["firstname"] . ' ' . $rent["customer"]["lastname"];
                }

                break;

            case 'newCustomer':
                if ($value === true)
                    $this->customer = $this->initialCustomer;
                break;

            case 'delete':
                if ($value === true) {
                    $this->rent["payments_count"] = count($this->rent["payments"]);
                    $this->modals["save"] = false;
                }
                break;
        }

        $this->modals[$name] = $value;
    }

    public function saveRent()
    {
        Validator::make($this->rent, [
            "name" => "required|" . $this->validations["text"],
            "car_id" => "required|exists:cars,id",
            "region_id" => "required|exists:regions,id",
            "start_date" => "required|date|after_or_equal:" . Carbon::now()->format("Y-m-d"),
            "end_date" => "required|date|after:start_date",
            "customer_id" => "required|exists:customers,id",
            "notes" => "nullable|" . $this->validations["textarea"],
        ])->validate();

        $this->rent["total"] = $this->getTotal();

        // validate car availability
        $rents = Rent::where('car_id', $this->rent["car_id"])->get();
        foreach ($rents as $rent) {
            if ($this->rent["start_date"] >= $rent->start_date && $this->rent["start_date"] <= $rent->end_date) {
                return $this->emit("toast", "error", __("calendar.car-not-available"));
            }
            if ($this->rent["end_date"] >= $rent->start_date && $this->rent["end_date"] <= $rent->end_date) {
                return $this->emit("toast", "error", __("calendar.car-not-available"));
            }
        }

        $rent = Rent::updateOrCreate(["id" => $this->rent["id"] ?? ""], $this->rent);

        $rent->products()->delete();
        foreach ($this->rent["products"] as $product) {
            $rent->products()->create([
                "product_id" => $product["product_id"],
                "quantity" => $product["quantity"],
            ]);
        }

        $this->Modal("save", true, $rent->load("products", "payments", "customer")->toArray());

        $this->emit("update-rent", $rent);
        $this->emit("toast", "success", __("calendar.save-success") . " " . $rent->name);

        $user = Auth::user();
        $rents = Rent::where('tenant_id', $user->tenant_id)->get();
        if ($rents->count() == 1) {
            $user->wizard_step = 5;
            $user->save();
        }
    }
    public function updateRents($action, $data)
    {
        if (isset($this->rent["id"]) && $this->rent["id"] == $data["id"]) {
            $rent = $this->rents->find($data["id"]);
            if ($rent)
                $this->rent = $rent->load("products", "payments", "customer", "car")->toArray();
            else
                $this->rent = $this->initialRent;
        }
    }

    public function productAction($product_id, $action, $quantity = 1)
    {
        $productIndex = array_search($product_id, array_column($this->rent["products"], "product_id"));

        switch ($action) {
            case 'add':
                if ($productIndex !== false) {
                    $this->rent["products"][$productIndex]["quantity"] += $quantity;
                } else {
                    $this->rent["products"][] = [
                        "quantity" => $quantity,
                        "product_id" => $product_id,
                    ];
                }
                break;

            case 'decrease':
                if ($productIndex !== false) {
                    if ($this->rent["products"][$productIndex]["quantity"] > 1) {
                        $this->rent["products"][$productIndex]["quantity"] -= $quantity;
                    } else {
                        unset($this->rent["products"][$productIndex]);
                    }
                }
                break;

            case 'remove':
                if ($productIndex !== false) {
                    unset($this->rent["products"][$productIndex]);
                }
                break;
        }

        $this->Modal("addProduct", false);
    }

    public function getTotal()
    {
        if (isset($this->rent['total']))
            return $this->rent['total'];

        $total = 0;
        if ($this->rent["products"])
            foreach ($this->rent["products"] as $data) {
                $total += $this->products->find($data["product_id"])->price * $data["quantity"];
            }

        if ($this->rent["region_id"] && $this->rent["start_date"] && $this->rent["end_date"]) {
            $region = $this->regions->find($this->rent["region_id"]);
            $start_date = Carbon::parse($this->rent["start_date"]);
            $end_date = Carbon::parse($this->rent["end_date"]);

            // if the rent days is negative then return 0
            if ($end_date->diffInDays($start_date) < 0)
                return $total;

            $days = $end_date->diffInDays($start_date);

            $currentRate = null;
            foreach ($region->rate_schedule as $rate) {
                if ($days == (int) $rate["days"]) {
                    $currentRate = (int) $rate["price"];
                    break;
                }
            }

            if (!$currentRate)
                $currentRate = $region->daily_rate;

            $total += $currentRate * $days;
        }

        return $total;
    }

    public function getRemaining()
    {
        $remaining = $this->rent["total"] ?? 0;
        foreach ($this->rent["payments"] as $payment) {
            $remaining -= $payment["amount"];
        }
        return $remaining;
    }

    public function addRentPayment()
    {
        if (!$this->rent["id"] || !Rent::find($this->rent["id"]))
            return $this->emit("toast", "error", __("calendar.rent-not-found"));

        Validator::make($this->payment, [
            "amount" => "required|" . "max:" . $this->getRemaining() . "|" . $this->validations["number"],
            "notes" => "required|" . $this->validations["textarea"],
        ])->validate();

        $this->payment["amount"] = (float) $this->payment["amount"];
        $this->payment["rent_id"] = $this->rent["id"];
        $this->payment["user_id"] = auth()->user()->id;

        $payment = RentPayment::create($this->payment);
        $this->rent["payments"][] = $payment;
        $this->payment = $this->initialRentPayment;

        $this->emit("toast", "success", __("calendar.RentPayment"));

        $this->handleCrudActions(
            "payment",
            [
                "action" => "create",
                "data" => $payment
            ]
        );

        $this->emit("update-rent", $this->rent);
    }

    public function newCustomer()
    {
        Validator::make($this->customer, [
            "firstname" => "required|" . $this->validations["text"],
            "lastname" => "required|" . $this->validations["text"],
            "email" => "required|" . $this->validations["email"],
            "phone" => "required|" . $this->validations["tel"],
            "address" => "required|" . $this->validations["textarea"],
            "notes" => "nullable|" . $this->validations["textarea"],
        ])->validate();

        $customer = Customer::create($this->customer);
        $this->setCustomer($customer->id);
        $this->customer = $this->initialCustomer;

        $this->Modal("newCustomer", false);

        $this->handleCrudActions(
            "customer",
            [
                "action" => "create",
                "data" => $customer
            ]
        );

        $this->rent["customer_id"] = $customer->id;

        $this->emit("toast", "success", __('toast.Customeraddedsuccessfully'));
    }

    public function getSchedule()
    {
        $date = Carbon::parse($this->rent["date"]);
        $dayName = strtolower($date->format("l"));
        $schedule = $this->currentSpace
            ? $this->currentSpace->schedule[$dayName]
            : [
                'opening' => '00:00',
                'closing' => '00:00',
            ];

        return $schedule;
    }

    function updateEndTime()
    {
        if ($this->rent["end_time"])
            return;
        $this->rent["end_time"] = $this->rent["start_time"];
    }

    public function deleteRent($id)
    {
        $this->Modal("delete", false);

        $rent = Rent::find($id);
        if (!$rent)
            return;

        $rent->payments()->delete();
        $rent->products()->delete();

        $rent->delete();

        $this->handleCrudActions(
            "rent",
            [
                "action" => "delete",
                "data" => $this->rent
            ]
        );

        $this->emit("toast", "success", __('calendar.delete-success'));
    }
}
