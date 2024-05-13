<?php

namespace App\Http\Controllers;

use App\Http\Requests\MomoRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function handleSuccessPayment(Request $request)
    {
        $token = $request->input('token');
        $payment = Payment::where('token', $token)->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Invalid token',
                'status' => 0,
            ], 400);
        }

        $payment->status = '1';
        $payment->save();

        return response()->json([
            'message' => 'Payment successfully',
            'status' => 1,
            'user_id' => $payment->user_id,
            'course_id' => $payment->course_id,
        ], 200);
    }

    public function momoPayment(MomoRequest $request)
    {
        $amount = $request->amount;
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = time() . "";
        $token = Str::random(32);
        $redirectUrl = "http://localhost:3000/user/check-out/success";

        $user = JWTAuth::parseToken()->authenticate();
        $existingPayment = Payment::where('user_id', $user->id)
        ->where('course_id', $request->course_id)
        ->first();

        if ($existingPayment) {
            return response()->json([
            'message' => 'Payment already exists for this user and course',
            'payment' => $existingPayment,
            ], 400);
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'status' => '0',
            'payment_type' => $request->payment_type,
            'amount' => $amount,
            'token' => $token,
        ]);
        $redirectUrl .= "?token=" . $token;


        $ipnUrl = "http://localhost:8000/api/user/check-out/success";
        $requestId = time() . "";
        $requestType = "payWithATM";
        $extraData = "extraData";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
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
            'token' => $token,
        ], 200);
    }
    public function vnPayment() {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
        $vnp_TmnCode = "CGXZLS0Z";
        $vnp_HashSecret = "XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN";

        $vnp_TxnRef = rand(00,99999);
        $vnp_OrderInfo = "Thanh toan bang momo";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = 10000 ;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'code' => '200',
            'message' => 'success',
            'data' => $vnp_Url
        );

        // Kiểm tra nếu có yêu cầu chuyển hướng, chuyển hướng đến URL thanh toán
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            exit(); // Kết thúc script sau khi chuyển hướng
        } else {
            // Nếu không, trả về dữ liệu dưới dạng JSON
            header('Content-Type: application/json');
            echo json_encode($returnData);
        }

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
