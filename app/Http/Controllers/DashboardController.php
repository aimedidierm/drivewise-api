<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Group;
use App\Models\Journey;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
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
}
