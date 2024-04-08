<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        $vehicles->load('group', 'user');
        return response()->json([
            'vehicles' => $vehicles
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::create([
            'name' => $request->input('name'),
            'plate' => $request->input('plate'),
            'load' => $request->input('load'),
            'fuel' => $request->input('fuel'),
            'fuel_type' => $request->input('fuel_type'),
            'group_id' => $request->input('group_id'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json([
            'message' => 'Vehicle created',
            'vehicle' => $vehicle,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::where('id', $id)->first();
        if ($vehicle) {
            return response()->json([
                'vehicle' => $vehicle,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Vehicle not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleRequest $request, string $id)
    {
        $vehicle = Vehicle::find($id);

        if ($vehicle) {
            $vehicle->update([
                'name' => $request->input('name'),
                'plate' => $request->input('plate'),
                'load' => $request->input('load'),
                'fuel' => $request->input('fuel'),
                'fuel_type' => $request->input('fuel_type'),
                'group_id' => $request->input('group_id'),
                'user_id' => $request->input('user_id'),
            ]);

            return response()->json([
                'message' => 'Vehicle updated',
                'vehicle' => $vehicle,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Vehicle not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::find($id);

        if ($vehicle) {
            $vehicle->delete();
            return response()->json([
                'message' => 'Vehicle deleted',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Vehicle not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
