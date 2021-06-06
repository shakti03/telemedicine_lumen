<?php

namespace App\Services\GoToMeeting;

use App\Models\GoToToken;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class GoToClient
{
    protected $clientId;
    protected $clientSecret;
    protected $accessTokenUrl;
    protected $authCode;
    protected $baseUrl;
    protected $refreshToken;
    protected $logger;

    public function __construct($token = null)
    {
        $this->clientId = config('goto.client_id');
        $this->clientSecret = config('goto.client_secret');
        // $this->redirectUri = config('goto.callback_url');
        // $this->authUrl = config('goto.auth_url');
        $this->accessTokenUrl = config('goto.access_token_url');
        $this->authCode = config('goto.auth_code');
        $this->baseUrl = config('goto.base_url');

        $goToToken = GoToToken::latest()->first();
        if ($goToToken) {
            $this->refreshToken = $goToToken->refresh_token;
        }

        $this->logger = Log::channel('gotomeeting');

        $this->logger->debug($this->accessTokenUrl);
    }


    /**
     * Get Authorization Code
     */
    public function getAuthCode()
    {
        $client = new GuzzleClient();
        $response = $client->request('GET', $this->authUrl, [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri
        ]);

        return $response;

        //callback_url?code=eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJscyI6ImU5ODRiYmMyLTE1ZjktNGI1MS1hOGU2LTExODAwODUzMWYzZCIsIm9nbiI6InB3ZCIsInVyaSI6Imh0dHBzOi8vd3d3LmdldHBvc3RtYW4uY29tL29hdXRoMi9jYWxsYmFjayIsInNjIjoiY2FsbHMudjIuaW5pdGlhdGUgY29sbGFiOiBjci52MS5yZWFkIGlkZW50aXR5OiBpZGVudGl0eTpzY2ltLm1lIG1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBtZXNzYWdpbmcudjEucmVhZCBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgdXNlcnMudjEubGluZXMucmVhZCIsImF1ZCI6ImE3Y2M0YTgyLTgwZGMtNDQ1OC04OWRjLWFjZGQxYWU2NjU1ZSIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiI1MTRhYjliYi00OGI0LTQwNzEtYjY1Mi01NjI5NWUzMWU5ZmIiLCJleHAiOjE2MTY5MDQyMzQsImlhdCI6MTYxNjkwMzYzNCwidHlwIjoiYyJ9.C8gasJVacVBVHLjeezbNfQpn7l_gEISOqc-QUUNQ3lQqTRG6KAP6DeiMs5QOZStylkrSnSGvEPNZ9hzM5jEGW6CvRBsZ3YqE3SlizD4xCD-cGHfpth887u9PliHuFyTym9lVdexATIb5DCCbU6zzemFFA5OL8VdVJgW4a8zoAYVV1A1ms293GvEkE68q_PN0SWfD14XbrmHQ3LWij1b-Yfh-IXujhKxhdxf-ykvBhpXgUd6Wp2MiJk1xFaI0i-ijV6w55DnExdtZX8k38cJ52GpbPY3T0Ci2roSiqY4zzsAeYA660RCbuliJui1Dk8jgVzccrsvYTRMRSajxdmcFEA
    }

    /**
     * Get Access Token
     */
    public function getAccessToken($authCode)
    {
        $client = new GuzzleClient();

        $requestHeaders = [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ":" . $this->clientSecret)
        ];
        $requestBody = [
            'grant_type' => 'authorization_code',
            'refresh_token' => $this->authCode,
            'client_id' => $this->clientId
        ];

        $response = $client->request('POST', $this->accessTokenUrl, [
            'headers' => $requestHeaders,
            'form_params' => $requestBody
        ]);

        return $response;
    }

    /**
     * Get Access Token From Refresh Token
     */
    public function getRefreshToken()
    {
        $client = new GuzzleClient();

        $requestHeaders = [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ":" . $this->clientSecret)
        ];
        $requestBody = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId
        ];

        $this->logger->debug('------- GotoMeeting : Get Access Token ----------');
        $this->logger->debug('------- Request Body ----------');
        $this->logger->debug([$requestHeaders, $requestBody]);

        $response = $client->request('POST', $this->accessTokenUrl, [
            'headers' => $requestHeaders,
            'form_params' => $requestBody
        ]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        $this->logger->debug('------- Response Body ----------');
        $this->logger->debug('Status Code: ' . $statusCode);
        $this->logger->debug($responseBody);

        if ($statusCode <= 201) {
            $token = GoToToken::latest()->first();
            $token->access_token = $responseBody['access_token'];
            // $token->token_type = "Bearer";
            $token->refresh_token = $responseBody['refresh_token']; //"eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJscyI6IjMzYTM3ZWJiLWJmZjMtNGU0NC04ODI1LTYwODgyYjk5YmRkOCIsIm9nbiI6InB3ZCIsImF1ZCI6IjdmOTQ4MDUzLTRkNDAtNDljOS1hZWI4LTIyMjc1M2ZhNzM1MyIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiJkYWNkYzJhMi1kODQyLTRhNGUtYmU1My03YmQ3Y2VkZmY1ZjMiLCJleHAiOjE2MTk1MDQwMTQsImlhdCI6MTYxNjkxMjAxNCwidHlwIjoiciJ9.HA9_7cYJLcbcXorVrbp3EqHZJdoAb4BchCtSfRWy8HQ6I2X8JaAmvQqJ8ktyxyo-ymut8gmCDOtg0Rd-qMz0K3wrAS8dKvgG9hzQM3FCf9fgVuvs0sIM8cNqrlDBpLhqEEP51TY1et53HMKUjs-3WJrrRYXdYlncNlIIBVnF51jXRGYx5VWLRMFTZXWWstd-omyFhIuxYqL9RZPOv87rLf_ytIVdxiPnJ907TyquxqIgG2nHO0OwPmPp7aykXZ_Ojz19UripSWWHN33hekqyP1fMgDWw3rVFJjZZMp2xVOwzE19nPDF2SkQh69-9-pvFrXMACv3z6gPAmn_u3Mi_Kg";
            // $token->expires_in = 3600;
            // $token->organizer_key = $responseBody['organizer_key']; //'6777911255743927694';
            // $token->other = "";
            $token->save();

            return json_decode($response->getBody(), true);
        }

        return null;

        /* Response Body
        {
            "access_token": "eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJvZ24iOiJwd2QiLCJhdWQiOiI3Zjk0ODA1My00ZDQwLTQ5YzktYWViOC0yMjI3NTNmYTczNTMiLCJzdWIiOiI2Nzc3OTExMjU1NzQzOTI3Njk0IiwianRpIjoiYzY3MWRkMWEtZjk4Yi00ZDg3LTk5ZDYtODc4MTkyNDY1M2Q1IiwiZXhwIjoxNjE2OTE1ODQzLCJpYXQiOjE2MTY5MTIyNDMsInR5cCI6ImEifQ.VTDCPUMc3sxdzb4EzLLegB2CltYjxoqaiIWOLpIoDfHJ0y1QdazvcKn0erF1arvJqr40zWmTsuIL0r6a0Zz2cudxrCHfhaCHHsl-U0oH-BvhSCgq-ZTfy1wapub8vvkP0zlxny10ppYzzit9wiM-WAlrqrPHGc8FRKdzVZZnd29bfgihv7RgYuPOppIZujaWGHThoxGrdqyW37IsdJ7H7BJLlC0iZXPZdXSzBBQJVYFT75ys7NaF9XF-vnilxOCwejDnR0LkLo0_oUnLi8VfxCUBDTrL5938g5_RFRxSliDfSW_NEge_JuEwW35f7Wly5sHxC0MHjETlc4h7GANkoA",
            "token_type": "Bearer",
            "refresh_token": "eyJraWQiOiJvYXV0aHYyLmxtaS5jb20uMDIxOSIsImFsZyI6IlJTNTEyIn0.eyJzYyI6Im1lc3NhZ2luZy52MS5ub3RpZmljYXRpb25zLm1hbmFnZSBjYWxscy52Mi5pbml0aWF0ZSBtZXNzYWdpbmcudjEuc2VuZCBtZXNzYWdpbmcudjEud3JpdGUgaWRlbnRpdHk6IG1lc3NhZ2luZy52MS5yZWFkIGNvbGxhYjogY3IudjEucmVhZCB1c2Vycy52MS5saW5lcy5yZWFkIGlkZW50aXR5OnNjaW0ubWUiLCJscyI6IjMzYTM3ZWJiLWJmZjMtNGU0NC04ODI1LTYwODgyYjk5YmRkOCIsIm9nbiI6InB3ZCIsImF1ZCI6IjdmOTQ4MDUzLTRkNDAtNDljOS1hZWI4LTIyMjc1M2ZhNzM1MyIsInN1YiI6IjY3Nzc5MTEyNTU3NDM5Mjc2OTQiLCJqdGkiOiJkYWNkYzJhMi1kODQyLTRhNGUtYmU1My03YmQ3Y2VkZmY1ZjMiLCJleHAiOjE2MTk1MDQwMTQsImlhdCI6MTYxNjkxMjAxNCwidHlwIjoiciJ9.HA9_7cYJLcbcXorVrbp3EqHZJdoAb4BchCtSfRWy8HQ6I2X8JaAmvQqJ8ktyxyo-ymut8gmCDOtg0Rd-qMz0K3wrAS8dKvgG9hzQM3FCf9fgVuvs0sIM8cNqrlDBpLhqEEP51TY1et53HMKUjs-3WJrrRYXdYlncNlIIBVnF51jXRGYx5VWLRMFTZXWWstd-omyFhIuxYqL9RZPOv87rLf_ytIVdxiPnJ907TyquxqIgG2nHO0OwPmPp7aykXZ_Ojz19UripSWWHN33hekqyP1fMgDWw3rVFJjZZMp2xVOwzE19nPDF2SkQh69-9-pvFrXMACv3z6gPAmn_u3Mi_Kg",
            "expires_in": 3600,
            "account_key": "1925427792805711502",
            "email": "developer.telemedicine@gmail.com",
            "firstName": "developer",
            "lastName": "telemedicine",
            "organizer_key": "6777911255743927694",
            "version": "3",
            "account_type": ""
        }
         */
    }


    /**
     * Create Meeting
     */
    public function createMeeting($payload)
    {
        /* Requst Body
            {
                "subject": "Meeting Madness",
                "starttime": "2021-06-12T12:00:00Z",
                "endtime": "2021-06-12T13:00:00Z",
                "passwordrequired": true,
                "conferencecallinfo": "VoIP",
                "timezonekey": "",
                "meetingtype": "scheduled"
            }
        */
        $authTokenResponse = $this->getRefreshToken();

        $this->logger->debug('------- GotoMeeting : Get Access Token ----------');
        $this->logger->debug('------- Request Body ----------');
        $this->logger->debug($payload);


        if ($authTokenResponse) {
            $client = new GuzzleClient();
            $response = $client->request('POST', $this->baseUrl . '/meetings', [
                "headers" => [
                    "Authorization" => $authTokenResponse['token_type'] . ' ' . $authTokenResponse['access_token'], //base64_encode($this->clientId . ':' . $this->secret),
                    "Content-Type" => "application/json",
                    "Accept" => "application/json"
                ],
                "json" => [
                    "subject" => $payload['subject'],
                    "starttime" => $payload['starttime'],
                    "endtime" => $payload['endtime'],
                    "passwordrequired" => false,
                    "conferencecallinfo" => "VoIP",
                    "timezonekey" => "",
                    "meetingtype" => "scheduled"
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody();

            $this->logger->debug('------- Response Body ----------');
            $this->logger->debug('Status Code: ' . $statusCode);
            $this->logger->debug($responseBody);

            if ($statusCode <= 201) {
                $result = json_decode($responseBody, true);
                return $result[0];
            }
        }

        return null;
    }
}
