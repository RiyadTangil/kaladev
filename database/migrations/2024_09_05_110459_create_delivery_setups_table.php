<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->double('kilometer_range');
            $table->decimal('minimum_order_amount', 13, 6);
            $table->decimal('delivery_charge', 13, 6);
            $table->string('creator_type',)->nullable();
            $table->bigInteger('creator_id',)->nullable();
            $table->string('editor_type',)->nullable();
            $table->bigInteger('editor_id',)->nullable();
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
        Schema::dropIfExists('delivery_setups');
    }
};