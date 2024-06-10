<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarComponent extends Component
{
    public $currentRoute;

    public $sidebar = [
        "dashboard" => [
            "icon" => "home",
            "route" => "dashboard.show"
        ],

        "more",

        "customers" => [
            "icon" => "brief-case",
            "route" => "tenant.customers.show",
            "permission" => "tenant.customers.show",
        ],
        "products" => [
            "icon" => "shopping-bag",
            "route" => "tenant.products.show",
            "permission" => "tenant.products.show",
        ],
        "cars" => [
            "icon" => "truck",
            "route" => "tenant.cars.show",
            "permission" => "tenant.cars.show",
        ],

        "regions" => [
            "icon" => "map-pin",
            "route" => "tenant.regions.show",
            "permission" => "tenant.regions.show",
        ],

        "payments" => [
            "icon" => "bank-notes",
            "route" => "tenant.payments.show",
            "permission" => "tenant.payments.show",
        ],
        "users" => [
            "icon" => "user-group",
            "route" => "tenant.users.show",
            "permission" => "tenant.users.show",
        ],

        "tenants" => [
            "icon" => "home-modern",
            "route" => "app.tenants",
            "permission" => "app.tenants.show",
        ],

        "plans" => [
            "icon" => "credit-card",
            "route" => "app.plans",
            "permission" => "app.plans.show",
        ],
        "taxes" => [
            "icon" => "credit-card",
            "route" => "tenant.taxes.show",
            "permission" => "tenant.taxes.show",
        ],
        "suppliers" => [
            "icon" => "brief-case",
            "route" => "tenant.suppliers.show",
            "permission" => "tenant.suppliers.show",
        ],

        "supplier-products" => [
            "icon" => "shopping-bag",
            "route" => "tenant.supplier-products.show",
            "permission" => "tenant.supplier-products.show",
        ],

    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar-component', ['currentRoute' => $this->currentRoute]);
    }
}
