<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

$receiveID = $_POST['receiveID'];



// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT profile_image FROM users WHERE receiveID = '$receiveID'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profileImage = $row['profile_image'];
    echo json_encode(array("imagePath" => $profileImage));
} else {
    echo json_encode(array("imagePath" => ""));
}

// MySQL 연결 종료
$conn->close();
?>
