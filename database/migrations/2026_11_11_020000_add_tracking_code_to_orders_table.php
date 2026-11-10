<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_code', 12)->nullable()->unique()->after('table_number');
        });

        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        foreach (DB::table('orders')->whereNull('tracking_code')->get() as $order) {
            do {
                $code = 'SB'.substr(str_shuffle(str_repeat($alphabet, 6)), 0, 6);
            } while (DB::table('orders')->where('tracking_code', $code)->exists());

            DB::table('orders')->where('id', $order->id)->update(['tracking_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tracking_code');
        });
    }
};
