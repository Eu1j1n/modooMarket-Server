<?php
// user.php.php


require_once 'config.php'; // 설정 파일 포함

$con = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

echo "기모띠";

if ($con)
{
    echo "접속 성공";
}
else
{
    echo "접속 실패";
}