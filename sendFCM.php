<?php
// FCM 서버로 알림을 보내는 PHP 스크립트

// 한국 시간으로 변환할 타임존을 설정
date_default_timezone_set('Asia/Seoul');

// 현재 시간을 가져와서 시와 분만 포함된 포맷으로 변환
$time = date('H:i'); // 시간과 분만 포함된 포맷으로 변환

// FCM 서버 URL
$url = 'https://fcm.googleapis.com/fcm/send';

// Google 클라우드 서비스 및 인증 라이브러리 로드
require_once ('./vendor/autoload.php');

// FCM 서버에 연결할 때 사용할 인증 키 설정
putenv('GOOGLE_APPLICATION_CREDENTIALS=./fcm_auth.json');

// 필요한 스코프 설정
$scope = 'https://www.googleapis.com/auth/firebase.messaging';

// Google 클라이언트 생성
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);

// 액세스 토큰 가져오기
$auth_key = $client->fetchAccessTokenWithAssertion();

// cURL 초기화
$ch = curl_init();

// 헤더 설정
$headers = array(
    'Authorization: Bearer ' . $auth_key['access_token'],
    'Content-Type: application/json'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// URL 설정
curl_setopt($ch, CURLOPT_URL, $url);

// SSL 검증 비활성화
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// 받은 데이터 처리
$token = isset($_POST['token']) ? $_POST['token'] : ''; // 클라이언트에서 전송한 토큰 값
$roomName = isset($_POST['roomName']) ? $_POST['roomName'] : ''; // 클라이언트에서 전송한 roomName 값
$title = "새로운 메시지 도착";
$message = "상대방이 메시지를 전송했습니다.";
$senderID = "상대 이름";
$messageContent = "마지막 메시지 내용";

// 푸시 알림 및 데이터 설정
$notification_opt = array(
    'title' => $title,
    'body' => $message
);
$android_opt = array(
    'notification' => array(
        'default_sound' => true
    )
);
$data = array(
    'token' => $token,
    'title' => $title,
    'message' => $message,
    'senderID' => $senderID,
    'messageContent' => $messageContent,
    'time' => $time,
    'roomName' => $roomName 
);

$message = array(
    'token' => $token,
    'notification' => $notification_opt,
    'android' => $android_opt,
    'data' => $data
);

// POST 요청 설정
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($message));

// cURL 실행 및 결과 출력
$result = curl_exec($ch);
if($result === FALSE){
    printf("cUrl error (#%d): %s<br>\n",
    curl_errno($ch),
    htmlspecialchars(curl_error($ch)));
}
echo $result;

// cURL 세션 종료
curl_close($ch);
?>
