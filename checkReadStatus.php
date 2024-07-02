<?php
// 클라이언트로부터 받은 메시지 ID
$messageID = $_POST['messageID'];

// 데이터베이스 연결
$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";
// MySQL 연결 생성
$conn = mysqli_connect($host, $user, $password, $db);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 읽음 상태를 확인하는 쿼리
$sql = "SELECT is_read FROM chatLog WHERE messageID = '$messageID'";
$result = $conn->query($sql);

// 응답 생성
if ($result->num_rows > 0) {
    // 메시지가 존재하는 경우
    $row = $result->fetch_assoc();
    $isRead = $row["is_read"];
    
    // 안읽은게 1이면 1을 반환, 읽은게 2이면 2를 반환
    echo $isRead;
} else {
    // 메시지가 존재하지 않는 경우
    echo "Message not found";
}

// 연결 종료
$conn->close();
?>
