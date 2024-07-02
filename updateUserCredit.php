<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$host = "localhost"; // 호스트 이름
$user = "root"; // MySQL 사용자 이름
$password = "Cjftlr224!"; // MySQL 비밀번호
$db = "userinfo"; // 사용할 데이터베이스 이름

// MySQL 데이터베이스에 연결합니다.
$con = mysqli_connect($host, $user, $password, $db);

// 연결 상태를 확인합니다.
if (!$con) {
    die("DB 연결 실패: " . mysqli_connect_error());
}


$userId = $_GET['userId'];
$priceString = $_GET['price'];



// 통화 기호와 쉼표를 제거하여 정수형 값으로 변환합니다.
$price = intval(str_replace(['₩', ','], '', $priceString));

// 사용자의 credit 값을 가져오기 위한 쿼리를 작성합니다.
$sql = "SELECT credit FROM users WHERE receiveID = '$userId'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // 사용자가 존재하는 경우
    $row = $result->fetch_assoc();
    $currentCredit = $row['credit'];

    // 새로운 credit 값을 계산합니다.
    $newCredit = $currentCredit - $price;
    

    // 계산된 credit 값을 업데이트하는 쿼리를 작성합니다.
    $updateSql = "UPDATE users SET credit = '$newCredit' WHERE receiveID = '$userId'";
    if ($con->query($updateSql) === TRUE) {
        // 업데이트가 성공한 경우
        echo "사용자 credit 업데이트 성공";
    } else {
        // 업데이트가 실패한 경우
        echo "사용자 credit 업데이트 실패: " . $con->error;
    }
} else {
    // 사용자가 존재하지 않는 경우
    echo "해당 사용자가 존재하지 않습니다.";
}

// MySQL 연결을 닫습니다.
$con->close();
?>
