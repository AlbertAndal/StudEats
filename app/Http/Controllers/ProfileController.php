<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'gender' => $request->gender,
            'height' => $request->height,
            'height_unit' => $request->height_unit ?? 'cm',
            'weight' => $request->weight,
            'weight_unit' => $request->weight_unit ?? 'kg',
            'activity_level' => $request->activity_level,
            'daily_budget' => $request->daily_budget,
            'timezone' => $request->timezone,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully! Your personalized meal recommendations will be updated.',
                'user' => $user->fresh()
            ]);
        }
        
        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully! Your personalized meal recommendations will be updated.');
    }
    
    /**
     * Show the form for changing password.
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }
    
    /**
     * Update the user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }
}

