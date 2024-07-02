<?php
// 데이터베이스 연결
$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 클라이언트에서 받은 사용자 ID
$userID = $_POST['userID']; 


// 사용자가 참여한 채팅 방의 마지막 메시지 정보를 가져오는 쿼리
$sql = "SELECT c1.room_name, c1.message_content, c1.currentTime 
        FROM chatLog c1
        INNER JOIN (
            SELECT room_name, MAX(id) AS lastMessageID
            FROM chatLog
            WHERE room_name LIKE '%$userID%'
            GROUP BY room_name
        ) c2 ON c1.id = c2.lastMessageID
        ORDER BY c1.id DESC";


$result = $conn->query($sql);

// 결과를 JSON 형식으로 변환하여 클라이언트에게 보내기
$response = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response); // JSON 배열 반환
} else {
    http_response_code(404); // HTTP 상태 코드 변경
    die();
}

// MySQL 연결 종료
$conn->close();
?>
