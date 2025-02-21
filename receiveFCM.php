<?php

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);


$userID = $_POST['userID'];
$token = $_POST['token'];




// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 아이디 있는지
$sql = "SELECT * FROM userToken WHERE user_id = '$userID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 이미 해당 사용자 ID가 테이블에 존재하면 업뎃
    $updateSql = "UPDATE userToken SET token = '$token' WHERE user_id = '$userID'";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "FCM 토큰이 성공적으로 업데이트되었습니다.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // 해당 사용자 ID가 테이블에 존재하지 않으면 삽입
    $insertSql = "INSERT INTO userToken (user_id, token) VALUES ('$userID', '$token')";
    
    if ($conn->query($insertSql) === TRUE) {
        echo "새로운 사용자 FCM 토큰이 성공적으로 저장되었습니다.";
    } else {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}

// MySQL 연결 닫기
$conn->close();

?>
