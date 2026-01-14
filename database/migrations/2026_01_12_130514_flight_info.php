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
         Schema::create('flights_info', function (Blueprint $table) {
            $table->id();
            $table->string('aircraft_id')->index();
            $table->string('callsign')->nullable();
            $table->string('origin_country');
            $table->timestamp('time_position')->nullable();
            $table->timestamp('last_contact');
            $table->double('longitude', 10, 6)->nullable();
            $table->double('latitude', 10, 6)->nullable();
            $table->double('baro_altitude')->nullable();
            $table->boolean('on_ground');
            $table->double('velocity')->nullable();
            $table->double('heading')->nullable();
            $table->double('vertical_rate')->nullable();
            $table->double('geo_altitude')->nullable();
            $table->string('transponder_code')->nullable();
            $table->boolean('special_position_indicator')->default(false);
            $table->integer('position_source')->nullable();
            $table->timestamps();
            
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropsIfexists("flights_info");
    }
};
