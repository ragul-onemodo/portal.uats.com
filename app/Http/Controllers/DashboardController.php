<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Entity;
use App\Models\Trip;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    protected string $module = 'dashboard';

    public function stats()
    {
        $labels = [];
        $weeklyTrips = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $labels[] = $date->format('d M');

            $weeklyTrips[] = Trip::whereDate('device_timestamp', $date)->count();
        }

        return response()->json([

            'entities' => Entity::count(),
            'users' => User::count(),
            'devices' => Device::count(),
            'trips' => Trip::count(),

            'todayTrips' => Trip::whereDate('device_timestamp', Carbon::today())->count(),

            'recentTrips' => Trip::latest('device_timestamp')
                ->take(5)
                ->get()
                ->map(function ($trip) {
                    return [
                        'vehicle' => $trip->vechicle_number,
                        'direction' => $trip->direction,
                        'weight' => $trip->weight,
                        'date' => Carbon::parse($trip->device_timestamp)
                            ->format('d M Y, h:i A'),
                    ];
                }),

            'deviceStatus' => [
                'active' => Device::where('is_active', 1)->count(),
                'inactive' => Device::where('is_active', 0)->count(),
        ],

            'weeklyLabels' => $labels,
            'weeklyTrips' => $weeklyTrips,

        ]);
    }

    public function index()
    {

        // dd(Auth::user()->roles()->pluck('name'), Auth::user()->getAllPermissions()->pluck('name'));

        return $this->view('index');
    }
}
