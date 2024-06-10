<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "tenant.admin" =>
                [
                    "tenant.customers.manage",
                    "tenant.customers.show",

                    "tenant.products.manage",
                    "tenant.products.show",

                    "tenant.cars.manage",
                    "tenant.cars.show",

                    "tenant.payments.manage",
                    "tenant.payments.show",

                    "tenant.users.manage",
                    "tenant.users.show",

                    "tenant.regions.manage",
                    "tenant.regions.show",

                    "tenant.taxes.manage",
                    "tenant.taxes.show",

                    "tenant.suppliers.manage",
                    "tenant.suppliers.show",

                    "tenant.supplier-products.manage",
                    "tenant.supplier-products.show",

                    "tenant.expenses.manage",
                    "tenant.expenses.show",
                ],
            "app.admin" => [
                "app.tenants.manage",
                "app.tenants.show",

                "app.plans.manage",
                "app.plans.show",
            ]
        ];

        foreach ($roles as $role => $permissions) {
            $role = Role::create(["name" => $role]);
            foreach ($permissions as $permission) {
                Permission::create(["name" => $permission]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
