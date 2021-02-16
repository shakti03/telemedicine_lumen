<?php

namespace App\Helpers;

use App\Models\PaymentJob;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaypalClient
{
    public function __construct()
    {
        //
    }

    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "<<PAYPAL-CLIENT-ID>>";
        $clientSecret = getenv("CLIENT_SECRET") ?: "<<PAYPAL-CLIENT-SECRET>>";
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    /**
     * Setting up the JSON request body for creating the Order. The Intent in the
     * request body should be set as "CAPTURE" for capture intent flow.
     * 
     */
    private static function buildRequestBody()
    {
        return array(
            'intent' => 'CAPTURE',
            'application_context' =>
            array(
                'return_url' => 'https://example.com/return',
                'cancel_url' => 'https://example.com/cancel',
                'brand_name' => 'TeleMedicine',
                // 'locale' => 'en-US',
                // 'landing_page' => 'BILLING',
                // 'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'user_action' => 'PAY_NOW',
            ),
            'purchase_units' =>
            [
                0 =>
                [
                    // 'reference_id' => 'PUHF',
                    // 'description' => 'Sporting Goods',
                    // 'custom_id' => 'CUST-HighFashions',
                    // 'soft_descriptor' => 'HighFashions',
                    'amount' =>
                    [
                        'currency_code' => 'USD',
                        'value' => '220.00'
                    ]
                ],
            ],
        );
    }

    public static function createOrder($data)
    {
        $request = new OrdersCreateRequest();
        $request->headers["prefer"] = "return=representation";
        $request->body = self::buildRequestBody();

        $client = self::client();
        $response = $client->execute($request);
        // if ($debug) {
        //     print "Status Code: {$response->statusCode}\n";
        //     print "Status: {$response->result->status}\n";
        //     print "Order ID: {$response->result->id}\n";
        //     print "Intent: {$response->result->intent}\n";
        //     print "Links:\n";
        //     foreach ($response->result->links as $link) {
        //         print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
        //     }
        //     // To toggle printing the whole response body comment/uncomment below line
        //     echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
        // }


        return $response;
    }
}
