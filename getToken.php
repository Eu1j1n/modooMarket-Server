<?php
// DB 연결 정보
require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// GET 요청으로부터 받은 사용자 아이디
$userId = $_GET['orderPeople'];

// MySQL 데이터베이스와 연결


// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자 아이디를 기반으로 토큰을 가져오는 쿼리
$sql = "SELECT token FROM userToken WHERE user_id = '$userId'";
$result = $conn->query($sql);

// 쿼리 결과 확인
if ($result->num_rows > 0) {
    // 결과가 하나 이상인 경우
    $row = $result->fetch_assoc();
    // JSON 형식으로 토큰 반환
    header('Content-Type: application/json');
    echo json_encode(array("token" => $row["token"]));
} else {
    // 결과가 없는 경우
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Token not found"));
}

// 연결 종료
$conn->close();
?>
