<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\InviteLink;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommonController extends Controller
{
    public function getSymptoms(Request $request)
    {
        return response()->json(DB::table('symptoms')->select('name')->where('name', 'LIKE', $request->term . '%')->take(30)->get());
    }

    /**
     * Share physician link to user emails
     */
    public function inviteViaEmail(Request $request)
    {
        $this->validate($request, [
            'link' => 'required',
            'emails' => 'required'
        ]);

        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $emails = $request->emails;

        foreach ($emails as $email) {
            Log::debug(Mail::to($email)->send(new InviteLink($user, $request->link)));
        }

        return response()->json(['message' => 'Invitation email has been sent successfully.']);
    }
}
