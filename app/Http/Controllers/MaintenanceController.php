<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

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
        $dates = [
            $request->input('date1'),
            $request->input('date2'),
            $request->input('date3')
        ];

        $intervalData = $this->calculateInterval($dates);

        $nextTime = Carbon::now();
        switch ($intervalData['unit']) {
            case 'minute':
                $nextTime->addMinutes($intervalData['interval']);
                break;
            case 'hour':
                $nextTime->addHours($intervalData['interval']);
                break;
            case 'day':
                $nextTime->addDays($intervalData['interval']);
                break;
            case 'week':
                $nextTime->addWeeks($intervalData['interval']);
                break;
            case 'month':
                $nextTime->addMonths($intervalData['interval']);
                break;
        }

        $maintenance = Maintenance::create([
            'title' => $request->input('title'),
            'notification' => $request->input('notification'),
            'interval' => $intervalData['interval'],
            'unit' => $intervalData['unit'],
            'vehicle_id' => $request->input('vehicle_id'),
            'next_time' => $nextTime
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

    private function calculateInterval($dates)
    {
        $intervals = [];
        for ($i = 0; $i < count($dates) - 1; $i++) {
            $intervals[] = Carbon::parse($dates[$i + 1])->diffInMinutes(Carbon::parse($dates[$i]));
        }

        $averageInterval = array_sum($intervals) / count($intervals);

        if ($averageInterval < 60) {
            return ['interval' => (int) $averageInterval, 'unit' => 'minute'];
        } elseif ($averageInterval < 1440) {
            return ['interval' => (int) ($averageInterval / 60), 'unit' => 'hour'];
        } elseif ($averageInterval < 10080) {
            return ['interval' => (int) ($averageInterval / 1440), 'unit' => 'day'];
        } elseif ($averageInterval < 40320) {
            return ['interval' => (int) ($averageInterval / 10080), 'unit' => 'week'];
        } else {
            return ['interval' => (int) ($averageInterval / 40320), 'unit' => 'month'];
        }
    }
}
