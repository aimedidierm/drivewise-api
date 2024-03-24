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
        $maintenance->load('vehicle');
        return response()->json([
            'maintenance' => $maintenance
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(MaintenanceRequest $request)
    // {
    //     $maintenance = Maintenance::create([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'phone' => $request->input('phone'),
    //         'password' => bcrypt($request->input('password')),
    //     ]);

    //     return response()->json([
    //         'maintenance' => 'Maintenance created',
    //         'driver' => $maintenance,
    //     ], Response::HTTP_OK);
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $maintenance = Maintenance::where('id', $id)->first();
        if ($maintenance) {
            $maintenance->load('vehicle');
            return response()->json([
                'maintenance' => $maintenance,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Maintenance not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(DriverRequest $request, string $id)
    // {
    //     $driver = User::find($id);

    //     if ($driver) {
    //         $driver->update([
    //             'name' => $request->input('name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'password' => bcrypt($request->input('password')),
    //         ]);

    //         return response()->json([
    //             'message' => 'Driver updated',
    //             'driver' => $driver,
    //         ], Response::HTTP_OK);
    //     } else {
    //         return response()->json([
    //             'message' => 'Driver not found',
    //         ], Response::HTTP_NOT_FOUND);
    //     }
    // }

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
