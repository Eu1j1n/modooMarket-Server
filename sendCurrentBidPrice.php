<?php
// MySQL 데이터베이스 연결 정보

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// 받은 데이터 가져오기
$title = $_GET['title'];
$location = $_GET['location'];
$price = $_GET['price'];
$userName = $_GET['userName'];

// 데이터베이스에서 데이터 가져오기 (LIMIT 1을 추가하여 결과를 한 행으로 제한)
$sql = "SELECT bidPrice
        FROM posts 
        WHERE title = '$title' AND location = '$location' AND price = '$price' AND userName = '$userName'
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); 

    // bidPrice에서 숫자만 추출
    $bidPriceOnlyNumbers = preg_replace('/[^0-9]/', '', $row['bidPrice']);

    // 추출된 숫자 문자열을 정수형으로 변환
    $bidPriceAsInt = intval($bidPriceOnlyNumbers);

    $data = array(
        'bidPrice'=> $bidPriceAsInt, // 정수형으로 변환된 현재 입찰가
    );

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(array('error' => 'No results found'));
}



// 데이터베이스 연결 닫기
$conn->close();
?>
