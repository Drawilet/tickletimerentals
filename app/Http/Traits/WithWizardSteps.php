<?php

namespace App\Http\Traits;

trait WithWizardSteps
{
    public $steps = [
        [
            "name" => "tenant",
            "route" => "settings.show"
        ],
        [
            "name" => "car",
            "route" => "tenant.cars.show"
        ],
        [
            "name" => "region",
            "route" => "tenant.regions.show"
        ],
        [
            "name" => "product",
            "route" => "tenant.products.show",
            "skippable" => true
        ],
        [
            "name" => "rent",
            "route" => "dashboard.show",
            "skippable" => true
        ]
    ];

}
