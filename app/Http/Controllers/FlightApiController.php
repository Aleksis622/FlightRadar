<?php

namespace App\Http\Controllers;

use App\Models\FlightInfo;
use Illuminate\Http\JsonResponse;

class FlightApiController extends Controller
{
    public function index()
    {
        return FlightInfo::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(250)
            ->get();
    }
}




