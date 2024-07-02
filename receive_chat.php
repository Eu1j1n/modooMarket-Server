<?php
// MySQL 데이터베이스 연결 설정
$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";

$conn = new mysqli($host, $user, $password, $db);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 서버 시간대를 한국 시간대로 설정
date_default_timezone_set("Asia/Seoul");

// 클라이언트에서 POST로 전송된 데이터 받기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $saveMessage = $_POST["saveMessage"];


    $messageParts = explode(">", $saveMessage);

    if (count($messageParts) == 6) {
        $roomName = $messageParts[0];
        $senderID = $messageParts[1];
        $messageContent = $messageParts[2];
        $currentTime = date("H:i"); //
        $currentDate = date("Y-m-d"); // 년-월-일 형식의 현재 날짜
        $messageID = $messageParts[5];

        $sql = "INSERT INTO chatLog (room_name, sender_id, message_content, currentTime, currentDate, messageID) VALUES ('$roomName', '$senderID', '$messageContent', '$currentTime', '$currentDate', '$messageID')";

        if ($conn->query($sql) === TRUE) {
            echo "Chat message saved successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
