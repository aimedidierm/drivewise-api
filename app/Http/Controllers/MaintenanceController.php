<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenance = Maintenance::all();
        $maintenance->load('vehicle.user');
        return response()->json([
            'maintenance' => $maintenance
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaintenanceRequest $request)
    {
        $maintenance = Maintenance::create([
            'title' => $request->input('title'),
            'notification' => $request->input('notification'),
            'interval' => $request->input('interval'),
            'unit' => $request->input('unit'),
            'vehicle_id' => $request->input('vehicle_id'),
        ]);

        return response()->json([
            'message' => 'Maintenance created',
            'maintenance' => $maintenance,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $maintenance = Maintenance::where('id', $id)->first();
        if ($maintenance) {
            $maintenance->load('vehicle.user');
            return response()->json([
                'maintenance' => $maintenance,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Maintenance not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MaintenanceRequest $request, string $id)
    {
        $maintenance = Maintenance::find($id);

        if ($maintenance) {
            $maintenance->update([
                'title' => $request->input('title'),
                'notification' => $request->input('notification'),
                'interval' => $request->input('interval'),
                'unit' => $request->input('unit'),
                'vehicle_id' => $request->input('vehicle_id'),
            ]);

            return response()->json([
                'message' => 'Driver updated',
                'maintenance' => $maintenance,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Maintenance not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $maintenance = Maintenance::find($id);

        if ($maintenance) {
            $maintenance->delete();
            return response()->json([
                'message' => 'Maintenance deleted',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Maintenance not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
