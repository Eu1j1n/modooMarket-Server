<?php
// login.php

require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

if (!$con) {
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiveID = $_POST['receiveID'];
    $receivePassword = $_POST['receivePassword'];

    // 해당 아이디와 비밀번호로 데이터베이스에서 조회
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $receiveID = $_POST['receiveID'];
        $receivePassword = $_POST['receivePassword'];
    
        // 해당 아이디와 비밀번호로 데이터베이스에서 조회
        $sql = "SELECT * FROM users WHERE receiveID = '$receiveID' AND password = '$receivePassword'";
        
        $result = mysqli_query($con, $sql);
    
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // 로그인 성공
                echo "success";
            } else {
                // 로그인 실패
                echo "fail";
            }
        } else {
            echo "error";
        }
    } else {
        echo "잘못된 요청";
    }
}    

mysqli_close($con);
?>
