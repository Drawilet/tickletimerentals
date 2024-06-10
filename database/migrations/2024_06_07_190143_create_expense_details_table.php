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
        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expense_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('concept');
            $table->string('unit');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('iva_percentage', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('amount', 10, 2);

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
        Schema::dropIfExists('expense_details');
    }
};
