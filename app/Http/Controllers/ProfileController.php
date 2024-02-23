<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->back();
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|max:255',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back();
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,gif|max:10240',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $newAvatar = $request->file('avatar')->store('avatar', 'public');
        $user->avatar = $newAvatar;
        $user->save();

        return redirect()->back();
    }
    
    public function updateBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,jpg,png,gif|max:10240',
        ]);

        $user = Auth::user();

        if ($user->background) {
            if (Storage::disk('public')->exists($user->background)) {
                Storage::disk('public')->delete($user->background);
            }
        }

        $newBackground = $request->file('background')->store('background', 'public');
        $user->background = $newBackground;
        $user->save();

        return redirect()->back();
    }
    
    public function updateDarkMode(Request $request)
    {
        $request->validate([
            'darkmode' => 'required',
        ]);

        $user = Auth::user();
        if ($request->darkmode == 'Yes') {
            $user->dark_mode = User::DARKMODE_YES;
        } else {
            $user->dark_mode = User::DARKMODE_NO;
        }
        $user->save();

        return redirect()->back();
    }
}
