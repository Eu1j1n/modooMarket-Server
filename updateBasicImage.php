<?php
// 서버 정보
$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

// POST 요청으로부터 아이디 값을 받아옴
$receiveID = $_POST['receiveID'];

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자의 profile_image 값을 NULL로 업데이트하는 쿼리
$sql = "UPDATE users SET profile_image = NULL WHERE receiveID = '$receiveID'";

// 쿼리 실행
if ($conn->query($sql) === TRUE) {
    // 업데이트 성공
    echo "Profile image updated successfully";
} else {
    // 업데이트 실패
    echo "Error updating profile image: " . $conn->error;
}

// 연결 종료
$conn->close();
?>
