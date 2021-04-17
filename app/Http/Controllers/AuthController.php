<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\User;

use App\Mail\VerifyEmail;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;

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

    /**
     * Verification registered user
     */
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

    /** 
     *  Send reset password email
     */
    public function generateResetToken(Request $request)
    {
        // Check email address is valid
        $this->validate($request, ['email' => 'required|email']);

        // Send password reset to the user with this email address
        $response = $this->passwordBroker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password verification mail has been sent to your email address.'])
            : response()->json(['message' => 'Failed to send reset link'], 400);
    }

    /**
     * Show Reset Password Page
     */
    public function showResetPassword(Request $request)
    {
        print_r($request->all());
    }

    // 2. Reset Password
    public function resetPassword(Request $request)
    {
        // Check input is valid
        $rules = [
            'token'    => 'required',
            'email' => 'required|string',
            'password' => 'required',
        ];
        $this->validate($request, $rules);

        // Reset the password
        $response = $this->passwordBroker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $user->password = app('hash')->make($password);
                $user->save();
            }
        );


        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json(['message' => 'Your password has been changed']);
            case Password::INVALID_TOKEN:
                return response()->json(['message' => 'Invalid Token'], 400);
            case Password::INVALID_USER:
                return response()->json(['message' => 'User does not exist'], 400);
            case Password::RESET_THROTTLED:
                return response()->json(['message' => 'Request limit over. Please try after some time.'], 400);
            default:
                return response()->json(['message' => 'Failed to change password'], 400);
        }

        // return $response == Password::PASSWORD_RESET
        //     ? response()->json(['message' => 'Your password has been changed'])
        //     : response()->json(['message' => $response], 400);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password', 'password_confirmation', 'token');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function passwordBroker()
    {
        $passwordBrokerManager = new PasswordBrokerManager(app());
        return $passwordBrokerManager->broker();
    }
}
