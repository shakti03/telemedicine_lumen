<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Models\User;

class AuthController extends BaseController
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

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (Hash::check($request->input('password'), $user->password)) {
            $user->generateToken();

            return response()->json($user->only('first_name', 'last_name', 'email', 'room_name', 'api_token'));
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }
}
