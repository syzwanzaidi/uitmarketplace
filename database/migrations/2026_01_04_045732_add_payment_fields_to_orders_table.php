<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('bill_code')->nullable()->after('status');
            $table->string('payment_ref')->nullable()->after('bill_code');
            $table->timestamp('paid_at')->nullable()->after('payment_ref');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bill_code', 'payment_ref', 'paid_at']);
        });
    }
};
