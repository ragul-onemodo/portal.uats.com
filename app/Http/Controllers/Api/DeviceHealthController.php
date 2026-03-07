<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceHealthController extends Controller
{
    public function heartbeat(Request $request)
    {

        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
        ]);
    }

}
