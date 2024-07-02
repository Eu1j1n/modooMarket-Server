<?php

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $roomName = $_GET["roomName"];

    $sql = "SELECT *, is_read AS isRead FROM chatLog WHERE room_name = '$roomName' ORDER BY currentDate, currentTime";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $chatMessages = array();

        while ($row = $result->fetch_assoc()) {
            $message = array(
                "Room" => $row["room_name"],
                "Sender" => $row["sender_id"],
                "Message" => $row["message_content"],
                "Time" => $row["currentTime"],
                "ChatDate" => $row["currentDate"],
                "isRead" => $row["isRead"], // 수정된 부분
                "MessageID" => $row["messageID"]

            );

            $chatMessages[] = $message;
        }

        echo json_encode($chatMessages);
    } else {
        echo "해당 방에 대한 채팅 메시지가 없습니다.";
    }
}


$conn->close();
?>
