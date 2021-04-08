<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Meeting;

use App\Services\PayPal\PayPalClient;

use App\Helpers\PaymentHelper;
use App\Helpers\GoToMeetingHelper;

use App\Mail\PaymentCompletedForPatient;
use App\Mail\PaymentCompletedForPhysician;
use App\Models\PaymentJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct()
    {
        //
        $this->paypal = new PayPalClient;
        $this->logger = Log::channel('paypal');
    }


    // public function createOrder(Request $request)
    // {
    //     $payment = new PaymentHelper;
    //     $result = $payment->createOrder([
    //         'appointment_id' => 2,
    //         'amount' => "100.00"
    //     ]);

    //     if ($result) {
    //         return redirect($result->payment_link);
    //     }

    //     return "request failed";
    // }

    /**
     * On Payment Approved by the Payer
     */
    public function onApprove(Request $request)
    {
        $this->logger->debug('--- Callback Capture Payment ');
        $this->logger->debug($request->all());

        $this->completePayment($request->token);

        return view('message', ['message' => "Congratulations! Your payment has been completed successfully."]);
    }

    /**
     * Complete Payment
     */
    private function completePayment($orderId)
    {
        $payment = new PaymentHelper;
        $result = $payment->captureOrder($orderId);

        if ($result) {
            $this->logger->debug($result);

            try {
                $transaction = new Payment;
                $transaction->appointment_id = $result->appointment_id;
                $transaction->amount = $result->amount;
                $transaction->paypal_order_ref = $result->paypal_order_token;
                $transaction->payee_ref = "";
                $transaction->result = $result->capture_result;
                $transaction->save();

                $gotoMeeting = null;
                if ($result->appointment->meeting_location == Meeting::LOCATION_GOTOMEETING) {
                    $gotoHelper = new GoToMeetingHelper;
                    $gotoMeeting = $gotoHelper->createGotoMeeting($result->appointment);
                }

                Mail::to($result->appointment->patient_email)->send(new PaymentCompletedForPatient($result->appointment, $gotoMeeting));
                Mail::to($result->appointment->meeting->user->email)->send(new PaymentCompletedForPhysician($result->appointment, $gotoMeeting));

                $result->appointment->payment_status = Appointment::PAYMENT_PAID;
                $result->appointment->save();
            } catch (\Exception $ex) {
                $this->logger->debug('Failed to create GoTo Meeting Link');
                $this->logger->debug($ex);
            }
        }
    }

    /**
     * On Payment Cancel
     */
    public function cancelOrder(Request $request)
    {
        $this->logger->debug('--- Callback Cancel Payment ');
        $this->logger->debug($request->all());

        if ($request->token) {
            $job = PaymentJob::wherePaypalOrderToken($request->token)->first();
            if ($job) {
                $job->capture_result = json_encode(["error" => "Payment cancelled by the user"]);
                $job->status = PaymentJob::STATUS_FAILED;
                $job->save();

                $job->appointment->payment_status = Appointment::PAYMENT_FAILED;
                $job->appointment->save();
            } else {
                $this->logger->debug("Token not found : " . $request->token);
            }
        } else {
            $this->logger->debug("Token not found : " . $request->token);
        }

        return view('message', ['message' => "Payment request cancelled."]);
    }


    /**
     * Payment approved / cancel
     */
    public function notifyPayment(Request $request)
    {
        $this->logger->debug('--- Callback Notify Payment ');
        $this->logger->debug($request->all());

        //$this->completePayment($request->resource["id"]);
    }
}
