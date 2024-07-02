<?php

header('Content-Type: application/json');

// DB 연결 설정
$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$dbName = "userinfo";

// 사용자 아이디 받아오기
$receiveID = $_GET['receiveID'];

// MySQL 연결
$conn = new mysqli($host, $user, $password, $dbName);

// 연결 오류 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자의 크레딧 값을 가져오는 SQL 쿼리 작성
$sql = "SELECT credit FROM users WHERE receiveID = ?";
$stmt = $conn->prepare($sql);

// 바인딩 및 실행
$stmt->bind_param("s", $receiveID);
$stmt->execute();
$stmt->store_result();

// 결과 저장을 위한 변수 초기화
$credit = 0;

// 결과 바인딩
$stmt->bind_result($credit);
$stmt->fetch();

// JSON 형태로 결과 반환
$result = array(
    'credit' => $credit
);
echo json_encode($result);

// 연결 종료
$stmt->close();
$conn->close();

?>
