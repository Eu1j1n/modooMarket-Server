<?php

// readStatus.php

// 필요한 데이터 가져오기
$roomName = $_POST['roomName'];

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 해당 방의 모든 메시지의 is_read 값을 2로 업데이트
$sql = "UPDATE chatLog SET is_read = 2 WHERE room_name = '$roomName'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

// 데이터베이스 연결 종료
$conn->close();

?>
