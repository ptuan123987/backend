<?php

namespace App\Http\Controllers\PayOS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayOS\PayOS;

class PaymentPayOsController extends Controller
{

    public function handlePayOSWebhook(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        if (in_array($body["data"]["description"], ["Ma giao dich thu nghiem", "VQRIO123"])) {
            return response()->json([
                "error" => 0,
                "message" => "Ok",
                "data" => $body["data"]
            ]);
        }

        $PAYOS_CLIENT_ID = config('services.payos.client_id');
        $PAYOS_API_KEY = config('services.payos.api_key');
        $PAYOS_CHECKSUM_KEY = config('services.payos.checksum_key');

        $webhookData = $body["data"];
        $payOS = new PayOS($PAYOS_CLIENT_ID, $PAYOS_API_KEY, $PAYOS_CHECKSUM_KEY);
        $payOS->verifyPaymentWebhookData($webhookData);

        return response()->json([
            "error" => 0,
            "message" => "Ok",
            "data" => $webhookData
        ]);
    }
}
