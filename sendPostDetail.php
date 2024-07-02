<?php
// MySQL 데이터베이스 연결 정보

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Seoul');

// 받은 데이터 가져오기
$title = $_GET['title'];
$location = $_GET['location'];
$price = $_GET['price'];
$userName = $_GET['userName'];
$receiveID = $_GET['receiveID'];

// 데이터베이스에서 데이터 가져오기 (LIMIT 1을 추가하여 결과를 한 행으로 제한)
$sql = "SELECT image_uri, title, location, send_time, price, userName, description, latitude, longitude, saleType, bidPrice, deadLineDate, state, likes
        FROM posts 
        WHERE title = '$title' AND location = '$location' AND price = '$price' AND userName = '$userName'
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $data = array();
    $row = $result->fetch_assoc(); 
    $imageUrls = explode(',', $row['image_uri']);

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

    // Likes에 쉼표로 구분된 데이터가 있는지 확인하고, receiveID가 있는지 여부를 반환
    $likesArray = explode(',', $row['likes']);
    $likeStatus = in_array($receiveID, $likesArray) ? 1 : 0;

    $post = array(
        'image_uri' => $imageUrls, 
        'title' => $row['title'],
        'location' => $row['location'],
        'send_time' => $row['send_time'],
        'price' => $row['price'],
        'userName' => $row['userName'],
        'description'=> $row['description'],
        'latitude'=> $row['latitude'],
        'longitude'=> $row['longitude'],
        'saleType'=> $row['saleType'],
        'bidPrice'=> $row['bidPrice'],
        'remaining_time' => $remaining_time,
        'state' => $row['state'],
        'likeStatus' => $likeStatus  // 좋아요 상태를 반환
    );
    $data[] = $post;
    
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "0 results";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
