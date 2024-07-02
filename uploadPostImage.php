<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = '/var/www/html/postImage/';
    $imageFieldName = 'image';
    
    if (isset($_FILES[$imageFieldName])) {
        $file = $_FILES[$imageFieldName];
        
        if ($file['error'] === UPLOAD_ERR_OK) {
           
            $allowed_extensions = array("jpg", "jpeg", "png");
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($file_extension, $allowed_extensions)) {
                echo json_encode(array('status' => 'error', 'message' => '올바른 이미지 형식이 아닙니다.'));
                exit;
            }
            
          
            $destination = $uploadDir . $file['name'];
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Success
                echo json_encode(array('status' => 'success', 'image_path' => $destination));
            } else {
                echo json_encode(array('status' => 'error', 'message' => '이미지 파일을 저장하는 동안 오류가 발생했습니다.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => '이미지 업로드 중 오류가 발생했습니다.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => '이미지 파일을 찾을 수 없습니다.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => '잘못된 요청입니다.'));
}
?>
