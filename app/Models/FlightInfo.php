<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightInfo extends Model
{
    protected $fillable = [
        'aircraft_id',
        'callsign',
        'origin_country',
        'time_position',
        'last_contact',
        'longitude',
        'latitude',
        'baro_altitude',
        'on_ground',
        'velocity',
        'heading',
        'vertical_rate',
        'geo_altitude',
        'transponder_code',
        'special_position_indicator',
        'position_source',
    ];

    public $timestamps = true;
}
