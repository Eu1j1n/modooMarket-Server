<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// GET 요청에서 전달된 값 가져오기
$title = $_GET['title'];
$location = $_GET['location'];
$price = $_GET['price'];
$userName = $_GET['userName'];
$receivedID = $_GET['successfulBidder'];

// 게시물 업데이트 SQL 쿼리
$sql = "UPDATE posts SET successfulBidder='$receivedID', state = 1 WHERE title='$title' AND location='$location' AND userName='$userName'";

// 쿼리 실행 및 결과 확인
if ($conn->query($sql) === TRUE) {
    echo "즉시구매자 등록 및 거래상태 판매 완료 설정.";
} else {
    echo "게시물 업데이트에 실패했습니다.: " . $conn->error;
}

// MySQL 연결 종료
$conn->close();
?>
