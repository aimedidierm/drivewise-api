<?php

namespace App\Http\Controllers;

use App\Enums\JourneyStatus;
use App\Http\Requests\JourneyRequest;
use App\Models\Journey;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JourneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $journeys = Journey::where('user_id', Auth::id())->get();
        $journeys->load('vehicle.user');
        return response()->json([
            'journeys' => $journeys
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JourneyRequest $request)
    {
        if ($request->input('load') > Auth::user()->vehicle->load) {
            return response()->json([
                'message' => "Your vehicle load can't be more than " . Auth::user()->vehicle->load . "Kg",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $journey = Journey::create([
            'location' => $request->input('location'),
            'destination' => $request->input('destination'),
            'distance' => $request->input('distance'),
            'load' => $request->input('load'),
            'status' => JourneyStatus::PENDING->value,
            'vehicle_id' => Auth::user()->vehicle->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Journey created',
            'journey' => $journey,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $journey = Journey::where('id', $id)->first();
        if ($journey) {
            $journey->load('vehicle.user');
            return response()->json([
                'journey' => $journey,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Journey not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
