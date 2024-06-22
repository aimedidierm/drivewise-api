<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\FuelVehicle;
use App\Models\Group;
use App\Models\Issue;
use App\Models\Journey;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $vehicles = Vehicle::count();
        $groups = Group::count();
        $drivers = User::where('type', UserType::DRIVER->value)->count();
        $journeys = Journey::count();
        return response()->json([
            'vehicles' => $vehicles,
            'groups' => $groups,
            'drivers' => $drivers,
            'journeys' => $journeys,
        ], Response::HTTP_OK);
    }

    public function driverDashboard()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())->count();
        $issues = Issue::where('user_id', Auth::id())->count();
        $fuel = FuelVehicle::where('user_id', Auth::id())->count();
        $journeys = Journey::where('user_id', Auth::id())->count();
        return response()->json([
            'vehicles' => $vehicles,
            'issues' => $issues,
            'fuel' => $fuel,
            'journeys' => $journeys,
        ], Response::HTTP_OK);
    }
}
