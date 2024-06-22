<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\DriverRequest;
use App\Mail\DriverCredentials;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = User::where('type', UserType::DRIVER->value)->get();
        $drivers->load('vehicle.group');
        return response()->json([
            'drivers' => $drivers
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DriverRequest $request)
    {
        $password = Str::random(6);
        $driver = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($password),
        ]);

        Mail::to($driver->email)->send(new DriverCredentials($driver->email, $password));

        return response()->json([
            'message' => 'Driver created',
            'driver' => $driver,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = User::where('type', UserType::DRIVER->value)->where('id', $id)->first();
        if ($driver) {
            $driver->load('vehicle.group');
            return response()->json([
                'driver' => $driver,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Driver not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest $request, string $id)
    {
        $driver = User::find($id);

        if ($driver) {
            $userData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ];

            if (!empty($request->input('password'))) {
                $userData['password'] = bcrypt($request->input('password'));
            }

            $driver->update($userData);

            return response()->json([
                'message' => 'Driver updated',
                'driver' => $driver,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Driver not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driver = User::find($id);

        if ($driver) {
            $driver->delete();
            return response()->json([
                'message' => 'Driver deleted',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Driver not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
