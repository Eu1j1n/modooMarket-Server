<?php
// user.php.php

$host = "localhost";
$user = "root";
$password = "Cjftlr224!";
$db = "userinfo";

$con = mysqli_connect($host, $user, $password, $db);

echo "기모띠";

if ($con)
{
    echo "접속 성공";
}
else
{
    echo "접속 실패";
}