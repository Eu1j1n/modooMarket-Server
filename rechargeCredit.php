<?php

// DB 연결 설정

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// CreditUpdateRequest 객체를 수신
$json = file_get_contents('php://input');
$request = json_decode($json);

// CreditUpdateRequest에서 데이터 추출
$receivedID = $request->receiveID;
$updatedCredit = $request->credit;

// MySQL 연결


// 연결 오류 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자의 현재 크레딧을 조회하는 SQL 쿼리 작성
$selectSql = "SELECT credit FROM users WHERE receiveID = ?";
$selectStmt = $conn->prepare($selectSql);
$selectStmt->bind_param("s", $receivedID);
$selectStmt->execute();
$selectResult = $selectStmt->get_result();

// 현재 크레딧 조회 결과 확인
if ($selectResult->num_rows > 0) {
    $row = $selectResult->fetch_assoc();
    $currentCredit = $row["credit"];
    
    // 기존 크레딧에 추가하여 새로운 크레딧 계산
    $updatedCredit += $currentCredit;
    
    // 사용자의 크레딧 값을 업데이트하는 SQL 쿼리 작성
    $updateSql = "UPDATE users SET credit = ? WHERE receiveID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("is", $updatedCredit, $receivedID);
    $updateResult = $updateStmt->execute();
    
    // 쿼리 실행 결과 확인
    if ($updateResult === TRUE) {
        echo "Credit updated successfully";
    } else {
        echo "Error updating credit: " . $conn->error;
    }
} else {
    echo "No user found with ID: " . $receivedID;
}

// 연결 종료
$selectStmt->close();
$updateStmt->close();
$conn->close();

?>
