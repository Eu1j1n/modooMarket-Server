<?php
// MySQL 데이터베이스 연결 정보
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

// 받은 데이터 가져오기
$title = $conn->real_escape_string($_GET['title']);
$location = $conn->real_escape_string($_GET['location']);
$priceStr = $conn->real_escape_string($_GET['price']);
$userName = $conn->real_escape_string($_GET['userName']);
$bidPriceStr = $conn->real_escape_string($_GET['updateBidPrice']);
$successfulBidder = $conn->real_escape_string($_GET['successfulBidder']);

// 입력받은 price에서 원화 기호(₩)와 쉼표(,) 제거 후 정수형으로 변환
$price = preg_replace("/[^\d]/", "", $priceStr);
$price = (int)$price;

// 입력받은 bidPrice에서 원화 기호(₩)와 쉼표(,) 제거 후 정수형으로 변환
$bidPrice = preg_replace("/[^\d]/", "", $bidPriceStr);
$bidPrice = (int)$bidPrice;

// 해당 게시물의 현재 bidPrice와 즉시 구매가(price) 확인
$currentBidSql = "SELECT bidPrice, price FROM posts
                  WHERE title = '$title' AND location = '$location' AND userName = '$userName' LIMIT 1";
$result = $conn->query($currentBidSql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // 데이터베이스 값에서 price의 ₩와 , 제거 후 정수형으로 변환
    $currentMaxPrice = preg_replace("/[^\d]/", "", $row['price']);
    $currentMaxPrice = (int)$currentMaxPrice;
    
    // 데이터베이스 값에서 bidPrice의 ₩와 , 제거 후 정수형으로 변환
    $currentBidPrice = preg_replace("/[^\d]/", "", $row['bidPrice']);
    $currentBidPrice = (int)$currentBidPrice;
    

    // 또한 입력받은 bidPrice가 즉시 구매가보다 크거나 같으면 즉시 구매가를 사용하여 업데이트
    if ($bidPrice > $currentBidPrice && $bidPrice < $currentMaxPrice) {
        // 입력받은 bidPrice에 ₩와 쉼표 추가
        $bidPriceWithSymbol = "₩" . number_format($bidPrice);
    
        $updateSql = "UPDATE posts
                      SET bidPrice = '$bidPriceWithSymbol', successfulBidder = '$successfulBidder'
                      WHERE title = '$title' AND location = '$location' AND userName = '$userName'";
        
        if ($conn->query($updateSql) === TRUE) {
            // 업데이트가 성공하면 변환된 bidPrice를 반환
            $data = array('bidPrice' => $bidPriceWithSymbol, 'successfulBidder' => $successfulBidder);
        } else {
            $data = array('error' => 'Update failed');
        }
    }else {
        // 입력받은 bidPrice가 변환된 현재 bidPrice보다 작거나 같거나 즉시 구매가를 넘는 경우, 현재 bidPrice와 현재의 성공적인 입찰자 반환
        $data = array('bidPrice' => $currentBidPrice, 'currentSuccessfulBidder' => $row["successfulBidder"], 'currentMaxPrice' => $currentMaxPrice);
    }
} else {
    $data = array('error' => 'Post not found');
}

header('Content-Type: application/json');
echo json_encode($data);

// 데이터베이스 연결 닫기
$conn->close();
?>
