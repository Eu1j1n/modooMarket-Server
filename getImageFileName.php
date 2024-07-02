<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

$receiveID = $_POST['receiveID'];

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);

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
