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
            'number' => 'required|string|max:255|unique:buses',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer',
        ]);

        $bus = Bus::create($request->all());
        return response()->json($bus, 201);
    }
}
