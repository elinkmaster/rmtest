<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        $user = auth()->user();
        $user->update($request->all());

        return redirect(route('user.profile'))->with('success','Profile successfully updated to the database');
    }

    public function editPassword()
    {
        return view('user.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:4'
        ]);


        if (Hash::check($request->current_password, auth()->user()->password)) {
            $user = auth()->user();
            $user->update(['password' => Hash::make($request->new_password)]);
            return redirect(route('user.edit-password'))->with('success', 'Password has been successfully updated!');
        }

        return redirect()->back()->withErrors(['current_password' =>"Password doesn't match the current password!"]);
    }
}
