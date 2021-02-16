<?php

namespace App\Helpers;

use App\Models\PaymentJob;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Helpers\PaypalClient;

class PaymentHelper
{
    const SUCCESS = 'success';

    public function __construct()
    {
        //
    }

    public function createOrder(array $data)
    {
        try {

            $paymentJob = new PaymentJob;
            $paymentJob->appointment_id = $data['appointment_id'];
            $paymentJob->amount = $data['amount'];
            $paymentJob->paypal_order_token = '';
            $paymentJob->payment_link = '';
            $paymentJob->result = '';
            $paymentJob->save();

            return (object) [
                'payment_link' => $paymentJob->payment_link
            ];
        } catch (\Exception $ex) {

            return (object) [
                'error' => $ex->getMessage()
            ];
        }
    }
}
