<?php
// register.php

$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";

$con = mysqli_connect($host, $user, $password, $db);

if ($con) {
    echo "접속 성공";
} else {
    echo "접속 실패"; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];
    $bank = $_POST['bank'];
    $account = $_POST['account'];
    $receiveID = $_POST['receiveID']; 

    $sql = "INSERT INTO users (name, password, address, phoneNumber, bank, account, receiveID) VALUES ('$name', '$password', '$address', '$phoneNumber', '$bank', '$account', '$receiveID')";

    if (mysqli_query($con, $sql)) {
        echo "회원가입이 완료되었습니다!";
    } else {
        echo "회원가입에 실패하였습니다. 오류: " . mysqli_error($con);
    }
}
?>
