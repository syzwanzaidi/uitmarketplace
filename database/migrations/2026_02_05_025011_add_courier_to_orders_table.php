<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasColumn('orders', 'courier')) {
            return; // some databases had this column added by hand
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier')->nullable()->after('tracking_number');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('courier');
        });
    }
};
