<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\FlightInfo;
use Carbon\Carbon;

class FlightsInfoSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://opensky-network.org/api/states/all');

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data['states'])) {
                foreach ($data['states'] as $state) {
                    FlightInfo::updateOrCreate(
                        [
                            'aircraft_id' => $state[0],
                        ],
                        [
                            'callsign' => trim($state[1]) ?: null,
                            'origin_country' => $state[2] ?? null,
                            'time_position' => $state[3] ? Carbon::createFromTimestamp($state[3]) : null,
                            'last_contact' => $state[4] ? Carbon::createFromTimestamp($state[4]) : now(),
                            'longitude' => $state[5],
                            'latitude' => $state[6],
                            'baro_altitude' => $state[7],
                            'on_ground' => $state[8],
                            'velocity' => $state[9],
                            'heading' => $state[10],
                            'vertical_rate' => $state[11],
                            'geo_altitude' => $state[13],
                            'transponder_code' => $state[14] ?: null,
                            'special_position_indicator' => $state[15] ?? false,
                            'position_source' => $state[16] ?? null,
                        ]
                    );
                }

                $this->command->info("Flights data imported: " . count($data['states']));
            } else {
                $this->command->warn("No flight states found.");
            }
        } else {
            $this->command->error("Failed to fetch data from API link");
        }
    }
}
