<?php
// 데이터베이스 연결 설정
require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST 요청으로부터 데이터 받아오기
$title = $_POST['title'];
$location = $_POST['location'];
// 한국 시간대로 현재 날짜와 시간을 가져오기
date_default_timezone_set('Asia/Seoul');
$sendTime = date('Y-m-d H:i:s');
$price = $_POST['price'];
$userName = $_POST['userName'];
$description = $_POST['description'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$saleType = $_POST['saleType'];
$bidPrice = $_POST['bidPrice'];

// deadLineDate 값 검증 및 기본값 설정
$deadLineDate = $_POST['deadLineDate'];
if(empty($deadLineDate) || !is_numeric($deadLineDate)) {
    $deadLineDate = "7"; // 기본값을 7일로 설정
}
$deadLineDateTime = date('Y-m-d H:i:s', strtotime("+$deadLineDate days"));

// 이미지 파일 이름들을 저장할 문자열
$imageNamesString = "";

// 이미지 파일 이름들을 POST 데이터에서 추출하여 문자열로 결합
foreach ($_POST as $key => $value) {
    // 이미지 파일 이름의 키는 "image"로 시작하는 것으로 가정
    if (strpos($key, 'image') === 0) {
        // 이미지 파일 이름을 문자열에 추가
        $imageNamesString .= $value . ",";
    }
}

// 마지막 쉼표 제거
$imageNamesString = rtrim($imageNamesString, ",");

// SQL 쿼리 작성하여 데이터베이스에 삽입
$sql = "INSERT INTO posts (image_uri, title, location, send_time, price, userName, description, latitude, longitude, saleType, bidPrice, deadLineDate) VALUES ('$imageNamesString', '$title', '$location', '$sendTime', '$price', '$userName', '$description', '$latitude', '$longitude', '$saleType', '$bidPrice', '$deadLineDateTime')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
