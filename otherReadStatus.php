<?php
// MySQL 데이터베이스 연결 설정
$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";

// 데이터베이스에 연결
$conn = new mysqli($host, $user, $password, $db);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 클라이언트에서 POST로 전송된 데이터 받기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 전달된 메시지 ID 가져오기
    $messageID = $_POST["messageID"];

    // 메시지 읽음 상태를 업데이트하는 SQL 쿼리
    $sql = "UPDATE chatLog SET is_read = 2 WHERE messageID = ?";

    // 매개변수화된 쿼리를 준비합니다.
    $stmt = $conn->prepare($sql);

    // 매개변수에 값을 바인딩하고 실행합니다.
    $stmt->bind_param("s", $messageID); // "s"는 문자열을 의미합니다.
    $stmt->execute();

    // 쿼리 실행 결과를 확인합니다.
    if ($stmt->affected_rows > 0) {
        echo "Message read status updated successfully";
    } else {
        echo "Error updating message read status";
    }
    
    // 명시적으로 준비된 문을 닫습니다.
    $stmt->close();
}

// 데이터베이스 연결 종료
$conn->close();
?>
