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
            "name" => "region",
            "route" => "tenant.regions.show"
        ],

        [
            "name" => "car",
            "route" => "tenant.cars.show"
        ],

        [
            "name"=>"tax",
            "route"=>"tenant.taxes.show",
            //Es por ahora luego se retira el skippable
            "skippable"=>true
        ],

        [
            "name" => "product",
            "route" => "tenant.products.show",
            "skippable" => true
        ],
        [

            "name"=>"suppliers",
            "route"=>"tenant.suppliers.show",
            //Es por ahora luego se retira el skippable
            "skippable"=>true

        ],
        [
            "name" => "rent",
            "route" => "dashboard.show",
            "skippable" => true
        ]
    ];


}
