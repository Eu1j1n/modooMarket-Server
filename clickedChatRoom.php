<?php
// MySQL 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

// POST 요청으로부터 데이터 가져오기
$receivedID = $_POST['receivedID'];
$hideRoomName = $_POST['hideRoomName'];

// 쿼리 생성
$sql = "UPDATE chatLog SET is_read = 2 WHERE room_name = '$hideRoomName' AND sender_id != '$receivedID'";

// 쿼리 실행
if ($conn->query($sql) === TRUE) {
    echo "채팅방에서 발신자와 수신자가 다른 모든 메시지의 is_read 값을 2로 업데이트했습니다.";
} else {
    echo "쿼리 실행 실패: " . $conn->error;
}

// MySQL 연결 종료
$conn->close();
?>
