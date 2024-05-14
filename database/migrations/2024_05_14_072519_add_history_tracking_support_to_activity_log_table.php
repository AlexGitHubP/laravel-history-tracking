<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->enum('type', array('activity_tracking', 'history_tracking'))->nullable()->after('properties');
            $table->integer('owner')->unsigned()->nullable()->index()->after('type');
            $table->dateTime('performed_on')->nullable()->after('owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('owner');
            $table->dropColumn('performed_on');
        });
    }
};
