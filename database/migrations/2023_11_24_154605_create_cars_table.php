<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->text("description");
            $table->string("color");

            $table->string("plate_number");
            $table->string("brand");
            $table->string("model");
            $table->string("year");
            $table->string("fuel_type");
            $table->string("transmission");
            $table->string("engine");
            $table->string("seats");
            $table->string("doors");

            $table->json("features");
            $table->json("prices");

            $table->text('notes')->nullable();

            $table->foreignId("tenant_id")->constrained("tenants");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
