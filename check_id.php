<?php
// checkId.php

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);



if (!$con) {
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiveID = mysqli_real_escape_string($con, $_POST['receiveID']); // POST 데이터의 key를 지정하고 변수명을 변경

    // 해당 아이디로 이미 데이터베이스에서 조회
    $sql = "SELECT * FROM users WHERE receiveID = '$receiveID'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // 이미 사용 중인 아이디
            echo "fail";
        } else {
            // 사용 가능한 아이디
            echo "success";
        }
    } else {
        echo "error";
    }
}


mysqli_close($con);
?>
