<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\FuelVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FuelVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->type == UserType::ADMIN->value) {
            $fuels = FuelVehicle::all();
            $fuels->load('user.vehicle');
        } else {
            # code...
        }

        return response()->json([
            'fuels' => $fuels
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fuel = FuelVehicle::where('id', $id)->first();
        if ($fuel) {
            $fuel->load('user.vehicle');
            return response()->json([
                'fuel' => $fuel,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Fuel refilling not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FuelVehicle $fuelVehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FuelVehicle $fuelVehicle)
    {
        //
    }
}
