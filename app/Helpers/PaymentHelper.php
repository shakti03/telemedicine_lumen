<?php

namespace App\Helpers;

use App\Models\PaymentJob;

use App\Services\PayPal\PayPalClient;

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PaymentHelper
{
    const SUCCESS = 'success';
    protected $paypal;
    protected $logger;

    public function __construct()
    {
        $this->paypal = new PayPalClient;
        $this->logger = Log::channel('paypal');
    }

    /**
     * Create Payment Order
     */
    public function createOrder($data)
    {
        $request = new OrdersCreateRequest();
        $request->headers["prefer"] = "return=representation";
        $request->body = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => URL::to('/paypal/return'),
                'cancel_url' => URL::to('/paypal/cancel'), //'https://example.com/cancel',
                'brand_name' => 'TeleMedicine',
                'locale' => 'en-US',
                'user_action' => 'PAY_NOW',
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $data["amount"]
                    ]
                ]
            ]
        ];

        $this->logger->debug('---- Create Order Request -----');
        $this->logger->debug($request->body);

        try {
            $client = PayPalClient::client();
            $response = $client->execute($request);
            $response = (object) $response;

            $this->logger->debug('---- Create Order Response -----');
            $this->logger->debug((array) $response);

            if ($response->statusCode <= 201) {

                $paymentJob = new PaymentJob;
                $paymentJob->appointment_id = $data['appointment_id'];
                $paymentJob->paypal_order_token = $response->result->id;

                foreach ($response->result->links as $link) {
                    if ($link->rel == 'approve') {
                        $paymentJob->payment_link = $link->href;
                        break;
                    }
                }

                $paymentJob->amount = $response->result->purchase_units[0]->amount->value;
                $paymentJob->order_result = json_encode((array) $response->result);
                $paymentJob->save();

                return $paymentJob;
            }
        } catch (\Exception $ex) {
            $this->logger->debug('------- Error in create Order -----');
            $this->logger->debug($ex);
        }

        return null;
    }

    /**
     * Capture Paypal Authorized Order
     */
    public function captureOrder($orderId)
    {
        try {
            $job = PaymentJob::wherePaypalOrderToken($orderId)->first();

            if (!$job) {
                return null;
            }

            $this->logger->debug('---------------- Capture Order ' . $orderId);
            $request = new OrdersCaptureRequest($orderId);

            $client = PayPalClient::client();
            $response = (object) $client->execute($request);
            $this->logger->debug((array) $response->result);

            if ($response->statusCode <= 201) {
                $job->capture_result = json_encode($response->result);
                $job->status = PaymentJob::STATUS_COMPLETED;
                $job->save();

                return $job;
            }
        } catch (\Exception $ex) {
            $this->logger->debug('------- Error in capture Order ' . $orderId . '-----');
            $this->logger->debug($ex);

            $job->capture_result = $ex->getMessage();
            $job->status = PaymentJob::STATUS_FAILED;
            $job->save();
        }

        return null;
    }
}
