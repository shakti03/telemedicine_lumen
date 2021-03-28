<?php

namespace App\Http\Controllers;

use App\Services\GoToMeeting\GoToClient;

class TestGotoMeeting extends Controller
{
    public function createMeeting()
    {
        $refreshToken = "eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJscyI6IjMzYTM3ZWJiLWJmZjMtNGU0NC04ODI1LTYwODgyYjk5YmRkOCIsIm9nbiI6InB3ZCIsImF1ZCI6IjdmOTQ4MDUzLTRkNDAtNDljOS1hZWI4LTIyMjc1M2ZhNzM1MyIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiJkYWNkYzJhMi1kODQyLTRhNGUtYmU1My03YmQ3Y2VkZmY1ZjMiLCJleHAiOjE2MTk1MDQwMTQsImlhdCI6MTYxNjkxMjAxNCwidHlwIjoiciJ9.HA9_7cYJLcbcXorVrbp3EqHZJdoAb4BchCtSfRWy8HQ6I2X8JaAmvQqJ8ktyxyo-ymut8gmCDOtg0Rd-qMz0K3wrAS8dKvgG9hzQM3FCf9fgVuvs0sIM8cNqrlDBpLhqEEP51TY1et53HMKUjs-3WJrrRYXdYlncNlIIBVnF51jXRGYx5VWLRMFTZXWWstd-omyFhIuxYqL9RZPOv87rLf_ytIVdxiPnJ907TyquxqIgG2nHO0OwPmPp7aykXZ_Ojz19UripSWWHN33hekqyP1fMgDWw3rVFJjZZMp2xVOwzE19nPDF2SkQh69-9-pvFrXMACv3z6gPAmn_u3Mi_Kg";
        $gotoClient = new GoToClient($refreshToken);
        $meeting = $gotoClient->createMeeting([
            "subject" => "Meeting Madness",
            "starttime" => "2021-06-12T12:00:00Z",
            "endtime" => "2021-06-12T13:00:00Z",
            "passwordrequired" => true,
            "conferencecallinfo" => "VoIP",
            "timezonekey" => "",
            "meetingtype" => "scheduled"
        ]);

        print_r($meeting);
    }
}
