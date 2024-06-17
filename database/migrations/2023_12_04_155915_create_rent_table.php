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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->foreignId('car_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('region_id')->constrained();

            $table->dateTime("start_date");
            $table->dateTime("end_date");

            $table->decimal("daily_rate");

            $table->foreignId("tax_id")->constrained();
            $table->decimal("tax_amount");

            $table->decimal("subtotal");
            $table->decimal("total");

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
        Schema::dropIfExists('rents');
    }
};
