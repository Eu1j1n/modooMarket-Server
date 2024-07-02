<?php

$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";

// POST 요청으로부터 roomName을 가져옴
$roomName = $_POST['roomName'];

// 데이터베이스 연결
$conn = new mysqli($host, $user, $password, $db);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// roomName을 사용하여 마지막 메시지의 시간을 조회
$sql = "SELECT currentTime FROM chatLog WHERE room_name = '$roomName' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 결과가 있으면 첫 번째 행의 currentTime 값을 반환
    $row = $result->fetch_assoc();
    $lastMessageTime = $row["currentTime"];
    echo $lastMessageTime;
} else {
    // 결과가 없으면 null 반환
    echo "null";
}

// 연결 종료
$conn->close();

?>
