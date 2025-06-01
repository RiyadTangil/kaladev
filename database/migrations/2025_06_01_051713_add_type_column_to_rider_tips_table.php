<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rider_tips', function (Blueprint $table) {
            if (!Schema::hasColumn('rider_tips', 'type')) {
                $table->enum('type', ['fixed', 'percentage'])->default('percentage')->after('amount');
            }
        });

        try {
            // Update all existing records to use percentage type
            DB::statement("UPDATE rider_tips SET type = 'percentage'");
        } catch (\Exception $e) {
            Log::error('Error updating rider_tips records: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rider_tips', function (Blueprint $table) {
            if (Schema::hasColumn('rider_tips', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
