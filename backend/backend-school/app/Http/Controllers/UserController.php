<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,teacher,staff,student,parent,driver',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'username' => $request->username,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return response()->json($user, 201);
    }
}
