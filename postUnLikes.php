<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// LikeData 객체에서 데이터 받아오기
$data = json_decode(file_get_contents('php://input'), true);



// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// LikeData 객체에서 데이터 추출
$title = $data['title'];
$location = $data['location'];
$price = $data['price'];
$userName = $data['userName']; // 게시물 작성자
$receiveID = $data['receiveID']; // 좋아요를 누른 사람

// 기존 좋아요 데이터 가져오기
$sql = "SELECT likes FROM posts WHERE title = '$title' AND location = '$location' AND price = '$price' AND userName = '$userName'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 기존 데이터가 있는 경우
    $row = $result->fetch_assoc();
    $existingLikes = $row['likes'];

    // 쉼표로 구분된 문자열을 배열로 변환
    $existingLikesArray = explode(",", $existingLikes);

    // 좋아요 취소
    $newLikesArray = [];
    foreach ($existingLikesArray as $id) {
        if ($id !== $receiveID) {
            $newLikesArray[] = $id;
        }
    }

    // 다시 쉼표로 구분된 문자열로 변환
    $newLikes = implode(",", $newLikesArray);
    
    // 사용자가 좋아요한 게시물 업데이트
    $sql = "UPDATE posts SET likes = '$newLikes' WHERE title = '$title' AND location = '$location' AND price = '$price' AND userName = '$userName'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    // 만약 모든 데이터가 삭제되었다면 레코드 업데이트 후, 빈 문자열로 업데이트
    if (empty($newLikesArray)) {
        $sql = "UPDATE posts SET likes = NULL WHERE title = '$title' AND location = '$location' AND price = '$price' AND userName = '$userName'";
        $conn->query($sql);
    }
} else {
    echo "No existing data found for the given criteria.";
}

// MySQL 연결 종료
$conn->close();
?>
