<?php

namespace App\Http\Controllers\PayOS;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayOSRequest;
use App\Models\Payment;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayOS\PayOS;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckoutController extends Controller
{
    public function createPaymentLink(PayOSRequest $request)
    {
        $YOUR_DOMAIN = "http://localhost:3000";
        $returnUrl = "http://localhost:3000/user/check-out/success";

        $user = JWTAuth::parseToken()->authenticate();

        if (!is_array($request->course_ids) || empty($request->course_ids)) {
            return response()->json([
                'message' => 'No courses provided',
            ], 400);
        }
        $amount = $request->amount;

        $items = [];
        $totalAmount = 0;
        $token = Str::random(32);
        foreach ($request->course_ids as $courseId) {
            $existingPayment = Payment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('status', "1")
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'message' => 'Payment already exists for this user and one of the courses',
                    'payment' => $existingPayment,
                ], 400);
            }

            $course = Course::find($courseId);
            Log::info($course->title);
            if (!$course) {
                return response()->json([
                    'message' => 'Course not found',
                ], 404);
            }


            Payment::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'status' => '0',
                'payment_type' => $request->payment_type,
                'amount' => $course->price,
                'token' => $token,
            ]);

            $items[] = [
                "name" => $course->title,
                "quantity" => 1,
                "price" => $course->price,
            ];

            $totalAmount += $course->price;
        }

        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => $totalAmount,
            "description" => "Thanh Toan Khoa Hoc",
            "buyerName" => $user->display_name,
            "buyerEmail" => $user->email,
            "buyerPhone" => "090xxxxxxx",
            "buyerAddress" => "số nhà, đường, phường, tỉnh hoặc thành phố",
            "items" => $items,
            "cancelUrl" => $YOUR_DOMAIN . "/cancel.html",
            "returnUrl" => $returnUrl . "?token=" . $token,
            "expiredAt" => Carbon::now()->addMinutes(15)->timestamp,
            "signature" => "",
        ];

        Log::info($request->input('amount'));
        // Generate the signature
        $data['signature'] = hash_hmac('sha256', json_encode($data), config('services.payos.checksum_key'));

        // PayOS credentials
        $PAYOS_CLIENT_ID = config('services.payos.client_id');
        $PAYOS_API_KEY = config('services.payos.api_key');
        $PAYOS_CHECKSUM_KEY = config('services.payos.checksum_key');

        // Create PayOS instance
        $payOS = new PayOS($PAYOS_CLIENT_ID, $PAYOS_API_KEY, $PAYOS_CHECKSUM_KEY);

        // Create payment link
        try {
            $response = $payOS->createPaymentLink($data);
            Log::info($response);
            return response()->json([
                "payUrl" =>  $response['checkoutUrl']
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
