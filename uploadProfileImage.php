<?php
header("Content-Type: text/html; charset=UTF-8");

$uploads_dir = "/var/www/html/newImage"; // 이미지를 저장할 디렉토리 경로
$allowed_extensions = array("jpg", "jpeg", "png"); // 허용된 파일 확장자 목록

$receiveID = $_POST['receiveID']; // 안드로이드 앱에서 보낸 receiveID 값을 받아옴


// 업로드된 파일 정보 확인
if ($_FILES['uploaded_file']['error'] == UPLOAD_ERR_OK) {
    $temp_name = $_FILES['uploaded_file']['tmp_name'];
    $original_name = $_FILES['uploaded_file']['name'];
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);

    // 파일 확장자를 체크하여 허용된 확장자인지 확인
    if (in_array($file_extension, $allowed_extensions)) {
        // 이미지 파일을 지정된 디렉토리로 이동
        if (move_uploaded_file($temp_name, "$uploads_dir/$original_name")) {
            // 이미지 업로드 성공

            // 데이터베이스 연결 설정
            $servername = "localhost";
            $username = "root";
            $password = "Cjftlr224!";
            $dbname = "userinfo";

            // MySQL 연결
            $conn = new mysqli($servername, $username, $password, $dbname);

            // 연결 확인
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

          
            $uploadedFileName = $original_name; // 업로드된 파일명 사용
            $sql = "UPDATE users SET profile_image = '$uploadedFileName' WHERE receiveID = '$receiveID'";

            if ($conn->query($sql) === TRUE) {
                echo "Profile image updated successfully";
            } else {
                echo "Error updating profile image: " . $conn->error;
            }

            // MySQL 연결 종료
            $conn->close();
        } else {
            // 이미지 업로드 실패
            echo "error";
        }
    } else {
        // 허용되지 않는 파일 확장자
        echo "invalid_extension";
    }
} else {
    // 업로드 오류
    echo "upload_error";
}
?>
