<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return response()->json($buses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:buses,number|max:255',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer',
        ]);

        $bus = Bus::create($request->all());
        return response()->json($bus, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:buses,number,' . $id,
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer',
        ]);

        $bus = Bus::find($id);
        if ($bus) {
            $bus->update($request->all());
            return response()->json($bus, 200);
        }
        return response()->json(['message' => 'Bus not found'], 404);
    }

    public function destroy($id)
    {
        $bus = Bus::find($id);
        if ($bus) {
            $bus->delete();
            return response()->json(['message' => 'Bus deleted successfully'], 200);
        }
        return response()->json(['message' => 'Bus not found'], 404);
    }
}
