<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\FuelRequest;
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
            $fuels = FuelVehicle::where('user_id', Auth::id())->get();
            $fuels->load('user.vehicle');
        }

        return response()->json([
            'fuels' => $fuels
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FuelRequest $request)
    {
        $fuel = FuelVehicle::create([
            'volume' => $request->input('volume'),
            'total' => $request->input('total'),
            'vehicle_id' => Auth::user()->vehicle->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Fuel filling created',
            'fuel' => $fuel,
        ], Response::HTTP_OK);
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
}
