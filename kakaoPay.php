<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 카카오페이 요청에 필요한 데이터 설정
$data = array(
    'cid' => 'TC0ONETIME',
    'partner_order_id' => 'partner_order_id',
    'partner_user_id' => 'partner_user_id',
    'item_name' => '초코파이',
    'quantity' => 1,
    'total_amount' => 2200,
    'vat_amount' => 200,
    'tax_free_amount' => 0,
    'approval_url' => 'https://developers.kakao.com/success',
    'fail_url' => 'https://developers.kakao.com/fail',
    'cancel_url' => 'https://developers.kakao.com/cancel'
);

// 카카오페이 요청 헤더 설정
$headers = array(
    'Authorization: SECRET_KEY ${SECRET_KEY}',
    'Content-Type: application/json'
);

// cURL을 사용하여 결제 준비 요청 보내기
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://open-api.kakaopay.com/online/v1/payment/ready');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// 응답 확인
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

?>
