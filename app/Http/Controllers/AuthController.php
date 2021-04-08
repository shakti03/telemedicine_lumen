<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\User;

use App\Mail\VerifyEmail;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Login User
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {

            if (!$user->email_verified_at) {
                return response()->json(['message' => 'User account is not verified yet.'], 401);
            }
            $user->generateToken();

            return response()->json($user->only('first_name', 'last_name', 'phone', 'email', 'room_name', 'api_token'));
        } else {
            return response()->json(['message' => 'Incorrect Username/password'], 401);
        }
    }

    /**
     * Register User
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'salutation' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        DB::beginTransaction();
        $user = new User;
        $user->salutation = $request->salutation;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->room_name = (explode('@', $request->email))[0];
        $user->save();

        try {
            Mail::to($user->email)->send(new VerifyEmail($user));
            DB::commit();
            return response()->json(['message' => 'You are registered successfully. Varification link has been sent to your email address.']);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error($ex);
            return response()->json(['message' => 'Failed to send verification email.'], 400);
        }
    }

    public function verifyUser(Request $request)
    {
        if (!$request->token) {
            abort(404);
        }

        $user = User::whereVerifyToken($request->token)->first();

        if (!$user) {
            abort(404);
        }

        if ($user->email_verified_at)
            return view('message', ['message' => "Your account is already verified.", 'link' => ["label" => "Login", 'url' => "/auth/login"]]);

        $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
        $user->save();

        return view('message', ['message' => "Your account has been verified successfully. Click below link to continue on Login page", 'link' => ["label" => "Login", 'url' => "/auth/login"]]);
    }
}
