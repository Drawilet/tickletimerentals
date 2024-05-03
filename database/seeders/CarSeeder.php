<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CarSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        $cars = [
            [
                "name" => "Peter Pipper Pizza",
                "description" => "Peter Pipper Pizza is a pizza restaurant in the heart of the city. We serve the best pizza in town. Come and visit us!",
                "address" => "123 Main Street",
                "city" => "New York",
                "state" => "NY",
                "country" => "USA",
                "schedule" => [
                    "monday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "tuesday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "wednesday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "thursday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "friday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "saturday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "sunday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ]
                ],
                "color" => "#f2ff3d",
            ],
            [
                "name" => "Versalles Palace",
                "description" => "Versalles Palace is a palace in the heart of the city. We serve the best pizza in town. Come and visit us!",
                "address" => "123 Main Street",
                "city" => "New York",
                "state" => "NY",
                "country" => "USA",
                "schedule" => [
                    "monday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "tuesday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "wednesday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "thursday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "friday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "saturday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ],
                    "sunday" => [
                        "opening" => "09:00",
                        "closing" => "17:00"
                    ]
                ],
                "color" => "#70fdff",
            ]
        ];

        foreach ($cars as $car) {
            $car["tenant_id"] = $user->tenant_id;
            $car = Car::create($car);

            $id = $car->id;
            $name = $car->name;

            $storagePath = "public/car/$id/photos";

            if (!Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath);
            }

            $photos = Storage::files("seeder/car/$name");

            foreach ($photos as $photo) {
                $photoName = basename($photo);
                Storage::copy($photo, "$storagePath/$photoName");
                CarPhoto::create([
                    "car_id" => $id,
                    "url" => "/storage/car/$id/photos/$photoName"
                ]);
            }
        }
    }
}
