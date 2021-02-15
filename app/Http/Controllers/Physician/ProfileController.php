<?php

namespace App\Http\Controllers\Physician;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get Profile
     *
     * @return JSON
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'room_name' => 'required|unique:users,room_name,' . $user->id
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->room_name = $request->room_name;
        $user->save();

        return response()->json(['data' => $user, 'message' => 'Profile updated successfully.']);
    }

    /**
     * Update Password
     *
     * @return JSON
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['data' => $user, 'message' => 'Password updated successfully.']);
    }
}
