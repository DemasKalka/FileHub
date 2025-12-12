<?php
session_start(); 
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}
$username = $_SESSION['username']; 
$target_dir = 'users/' . $username . '/'; 
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0755, true); 
}
$errors = [];
if (isset($_FILES["fileToUpload"])) {
    $files = $_FILES["fileToUpload"]; 
    foreach ($files['name'] as $key => $value) {
        $target_file = $target_dir . basename($files["name"][$key]); 
        if (move_uploaded_file($files["tmp_name"][$key], $target_file)) { 
            $_SESSION['upload_message'] = "File(s) uploaded successfully!";
        } else {
            $errors[] = "File(s) could not be uploaded.";
        }
    }
    if (!empty($errors)) {
        $_SESSION['upload_message'] = implode("<br>", $errors); 
    }
}
header("Location: dashboard.php");
?>
