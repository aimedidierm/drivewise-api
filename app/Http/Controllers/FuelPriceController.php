<?php

namespace App\Http\Controllers;

use App\Http\Requests\FuelPriceRequest;
use App\Models\FuelPrice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FuelPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prices = FuelPrice::all();
        return response()->json([
            'prices' => $prices
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FuelPriceRequest $request, string $id)
    {
        $price = FuelPrice::find($id);
        if ($price) {
            $price->update([
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'price' => $request->input('price'),
            ]);

            return response()->json([
                'message' => 'Price updated',
                'price' => $price,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Price not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
