<?php

return [
    'client_id' => env('GOTO_CLIENT_ID'),
    'client_secret' => env('GOTO_CLIENT_SECRET'),
    'base_url' => env('GOTO_REST_BASEURL'),
    'callback_url' => env('GOTO_CALLBACK_URL'),
    'auth_url' => env('GOTO_AUTH_URL'),
    'access_token_url' => env('GOTO_ACCESS_TOKEN_URL')
];

/*
client_id=a7cc4a82-80dc-4458-89dc-acdd1ae6655e
client_secret=IMvfWvlxClhg5bOpj71zEzaT
callback_url=https://www.getpostman.com/oauth2/callback
auth_url=https://api.getgo.com/oauth/v2/authorize
access_token_url=https://api.getgo.com/oauth/v2/token
base_url=https://api.getgo.com/G2M/rest
product="g2m"
*/