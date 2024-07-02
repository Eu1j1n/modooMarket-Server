<?php
// MySQL 데이터베이스 연결 정보
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "Cjftlr224!";
$dbname = "userinfo";

// MySQL 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 한국 시간대 설정
date_default_timezone_set('Asia/Seoul');

// 데이터베이스에서 데이터 가져오기
$sql = "SELECT image_uri, title, location, send_time, price, userName, description, saleType, bidPrice, deadLineDate, state FROM posts";
$result = $conn->query($sql);

// 결과 확인 및 출력
if ($result->num_rows > 0) {
    // 결과를 연관 배열로 변환하여 출력
    $data = array();
    while ($row = $result->fetch_assoc()) {
        // 이미지 URL을 쉼표로 구분하여 배열로 변환
        $imageUrls = explode(',', $row['image_uri']);
        // 첫 번째 이미지 URL만 선택
        $firstImageUrl = $imageUrls[0];

        // 현재 시간과 마감 시간 간의 차이 계산
        $currentTime = new DateTime('now', new DateTimeZone('Asia/Seoul'));
        $deadline = new DateTime($row['deadLineDate']);

        // 마감 시간이 현재 시간을 지났는지 확인
        if ($deadline < $currentTime) {
            // 마감 시간이 현재 시간을 지난 경우
            $remaining_time = '입찰 종료';
        } else {
            // 아직 마감 시간이 남은 경우
            $timeDiff = $currentTime->diff($deadline);
            if ($timeDiff->days > 0) {
                // 24시간 이상 남은 경우
                $days = $timeDiff->format('%a');
                $hours = $timeDiff->format('%H');
                $remaining_time = $days . "일 " . $hours . "시간 ";
            } else {
                // 24시간 이하 남은 경우
                $remaining_time = $timeDiff->format('%H:%I:%S');
            }
        }

        // 게시물 데이터 생성
        $post = array(
            'image_uri' => $firstImageUrl,
            'title' => $row['title'],
            'location' => $row['location'],
            'send_time' => $row['send_time'],
            'price' => $row['price'],
            'userName' => $row['userName'],
            'description'=> $row['description'],
            'saleType' => $row['saleType'],
            'bidPrice' => $row['bidPrice'],
            'remaining_time' => $remaining_time,
            'state' => $row['state']
        );
        $data[] = $post;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "0 results";
}
$conn->close();
?>
