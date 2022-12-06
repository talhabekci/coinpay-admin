<?php
session_start();
require 'config.php';

if (isset($_POST["submit"])) {

    $target_dir = "../../assets/img/avatars/";
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $filename = $_FILES["avatar"]["name"];

    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check == false) {
        exit(json_encode(["result" => null, "error" => "File is not an image."]));
    }

    if ($_FILES["avatar"]["size"] > 2000000) {
        exit(json_encode(["result" => null, "error" => "Avatar size is too big."]));
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        exit(json_encode(["result" => null, "error" => "Only JPG, JPEG, PNG & GIF files are allowed."]));
    }

    move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);

    $name = mysqli_real_escape_string($open, $_POST["fname"]);
    $surname = mysqli_real_escape_string($open, $_POST["lname"]);
    $email = mysqli_real_escape_string($open, $_POST["email"]);
    $password = mysqli_real_escape_string($open, $_POST["password"]);

    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `id` = '".$_SESSION["user_id"]."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $user_credentials = mysqli_fetch_array($result);

    if (!password_verify($password, $user_credentials["password"])) {
        exit(json_encode(["result" => null, "error" => "Wrong Password."]));
    }

    $result = mysqli_query($open, "UPDATE `cp_users` SET `user_avatar` = '".$filename."', `name` = '".$name."', `surname` = '".$surname."', `email` = '".$email."' WHERE `id` = '".$_SESSION["user_id"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
    }

    exit(json_encode(["result" => "Profile updated", "error" => NULL]));

}

?>
