<?php

namespace App\Http\Controllers\Physician;

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
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();

        return response()->json($user);
    }
}
