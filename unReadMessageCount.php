<?php
// 데이터베이스 연결 설정

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);
// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로 전달된 사용자 ID 가져오기
$receivedID = $_POST['receivedID'];

// 각 채팅방에서 로그인한 사용자가 아직 읽지 않은 메시지의 개수를 가져오는 쿼리 작성
$sql = "SELECT room_name, COUNT(*) AS unread_count FROM chatLog WHERE messageID = '$receivedID' AND is_read = 0 GROUP BY room_name";

// 쿼리 실행
$result = $conn->query($sql);

// 결과 배열 초기화
$response = array();

if ($result->num_rows > 0) {
    // 결과가 있으면 결과에서 각 채팅방의 안 읽은 메시지의 개수를 가져와서 배열에 추가
    while ($row = $result->fetch_assoc()) {
        $roomName = $row["room_name"];
        $unreadCount = $row["unread_count"];
        $response[$roomName] = $unreadCount;
    }
}

// JSON 형태로 응답
echo json_encode($response);

// MySQL 연결 종료
$conn->close();
?>
