<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Find the Klarna payment gateway ID
        $klarnaGateway = DB::table('payment_gateways')
            ->where('slug', 'klarna')
            ->first();

        if ($klarnaGateway) {
            // Delete any media associated with the Klarna payment gateway
            DB::table('media')
                ->where('model_type', 'App\\Models\\PaymentGateway')
                ->where('model_id', $klarnaGateway->id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing to revert since we're fixing an issue
    }
};
