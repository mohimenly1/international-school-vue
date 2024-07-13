<?php

namespace App\Http\Controllers;

use App\Models\ParentInfo;
use Illuminate\Http\Request;

class ParentInfoController extends Controller
{

    public function show($id)
{
    $parent = ParentInfo::with('user')->findOrFail($id);
    return response()->json($parent);
}

    public function index()
    {
        $parents = ParentInfo::with('user')->get();
        return response()->json($parents);
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => 'nullable|exists:users,id|unique:parent_infos',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number_one' => 'nullable|string|max:255',
            'phone_number_two' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'id_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'passport_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'images_info' => 'nullable|json'
        ]);
    
        if ($request->hasFile('id_image')) {
            $idImagePath = $request->file('id_image')->store('id_images', 'public');
        } else {
            $idImagePath = null;
        }
    
        if ($request->hasFile('passport_image')) {
            $passportImagePath = $request->file('passport_image')->store('passport_images', 'public');
        } else {
            $passportImagePath = null;
        }
    
        $parent = ParentInfo::create([
            // 'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number_one' => $request->phone_number_one,
            'phone_number_two' => $request->phone_number_two,
            'city' => $request->city,
            'address' => $request->address,
            'id_image' => $idImagePath,
            'passport_image' => $passportImagePath,
            'images_info' => $request->images_info,
        ]);
    
        return response()->json($parent, 201);
    }
    
}
