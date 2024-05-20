<?php

namespace App\Http\Controllers;

use App\Http\Requests\MomoRequest;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function handleSuccessPayment(Request $request)
    {
        $token = $request->input('token');

        $payments = Payment::where('token', $token)->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'message' => 'Invalid token',
                'status' => 0,
            ], 400);
        }

        $user_id = null;
        $course_ids = [];

        foreach ($payments as $payment) {
            $payment->status = '1';
            $payment->save();
            $user_id = $payment->user_id;
            $course_ids[] = $payment->course_id;
        }

        return response()->json([
            'message' => 'Payment successfully',
            'status' => 1,
            'user_id' => $user_id,
            'course_ids' => $course_ids,
        ], 200);
    }


    public function momoPayment(MomoRequest $request)
    {
        $courses = $request->course_ids;
        $user = JWTAuth::parseToken()->authenticate();
        $redirectUrl = "http://localhost:3000/user/check-out/success";

        $token = Str::random(32);

        $existingPayments = Payment::where('user_id', $user->id)
            ->where('status', '0')
            ->where('payment_type', $request->payment_type)
            ->get();

        $totalAmount = 0;

        foreach ($courses as $courseId) {
            $course = Course::find($courseId);

            if (!$course) {
                return response()->json([
                    'message' => 'Course not found',
                ], 404);
            }

            $totalAmount += $course->price;

            Payment::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'status' => '0',
                'payment_type' => $request->payment_type,
                'amount' => $course->price,
                'token' => $token,
            ]);
        }

        $redirectUrl .= "?token=" . $token;

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = time() . "";
        $ipnUrl = "http://localhost:8000/api/user/check-out/success";
        $requestId = time() . "";
        $requestType = "payWithATM";
        $extraData = "extraData";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $totalAmount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $totalAmount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'lang' => 'vi',
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        return response()->json([
            'payUrl' => $jsonResult['payUrl'],
        ], 200);
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

}
