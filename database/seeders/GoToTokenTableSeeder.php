<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\GoToToken;

class GoToTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token = new GoToToken;
        $token->access_token = "eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJscyI6IjMzYTM3ZWJiLWJmZjMtNGU0NC04ODI1LTYwODgyYjk5YmRkOCIsIm9nbiI6InB3ZCIsImF1ZCI6IjdmOTQ4MDUzLTRkNDAtNDljOS1hZWI4LTIyMjc1M2ZhNzM1MyIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiJjNDI2YjZiNS01NzAwLTRiM2YtYmY5Ni0wNDMwMjA4ZTk0YmQiLCJleHAiOjE2MTY5MTU2MTQsImlhdCI6MTYxNjkxMjAxNCwidHlwIjoiYSJ9.U5u3yP_TNLl13z0to3AjZZ0yVZXZu2tXYj4efvexblT38Ds36fuCueO67chOxxcJ9P5OHybMAsVSyzp79XYlO9bvbfKELlT9aKR-uxKkVpdWFvE757YibnEr7UZOY2nPJvwNGYRW2wp6aqRBu_hrjMRw-WrrFTpfWPRfj_cFu231A5UT39iD8ftjCm6EUFf7HH9DmF5LCZeZ-z1dJLaitiEoZbjyR7ODDP4uB5WBNJKLnUYwjmzwOqLFyIqSpLuoCxTO_5zy0ufGLQQg-pCmk2RyAnbUew6Lwbtceou68GU-ko6LGhxme6pbMqyqa1Kno-9Gl6JPNxRZTQbQWiVwVQ";
        $token->token_type = "Bearer";
        $token->refresh_token = "eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJscyI6IjMzYTM3ZWJiLWJmZjMtNGU0NC04ODI1LTYwODgyYjk5YmRkOCIsIm9nbiI6InB3ZCIsImF1ZCI6IjdmOTQ4MDUzLTRkNDAtNDljOS1hZWI4LTIyMjc1M2ZhNzM1MyIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiJkYWNkYzJhMi1kODQyLTRhNGUtYmU1My03YmQ3Y2VkZmY1ZjMiLCJleHAiOjE2MTk1MDQwMTQsImlhdCI6MTYxNjkxMjAxNCwidHlwIjoiciJ9.HA9_7cYJLcbcXorVrbp3EqHZJdoAb4BchCtSfRWy8HQ6I2X8JaAmvQqJ8ktyxyo-ymut8gmCDOtg0Rd-qMz0K3wrAS8dKvgG9hzQM3FCf9fgVuvs0sIM8cNqrlDBpLhqEEP51TY1et53HMKUjs-3WJrrRYXdYlncNlIIBVnF51jXRGYx5VWLRMFTZXWWstd-omyFhIuxYqL9RZPOv87rLf_ytIVdxiPnJ907TyquxqIgG2nHO0OwPmPp7aykXZ_Ojz19UripSWWHN33hekqyP1fMgDWw3rVFJjZZMp2xVOwzE19nPDF2SkQh69-9-pvFrXMACv3z6gPAmn_u3Mi_Kg";
        $token->expires_in = 3600;
        $token->organizer_key = '6777911255743927694';
        $token->other = "";
        $token->save();
    }
}
