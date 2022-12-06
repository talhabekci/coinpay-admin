<?php

require 'config.php';

if (!empty($_POST["submit"] && $_POST["fname"] && $_POST["lname"] && $_POST["email"] && $_POST["password"])) {
    $name = mysqli_real_escape_string($open, $_POST["fname"]);
    $surname = mysqli_real_escape_string($open, $_POST["lname"]);
    $email = mysqli_real_escape_string($open, $_POST["email"]);
    $password = mysqli_real_escape_string($open, password_hash($_POST["password"], PASSWORD_DEFAULT));

    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$email."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        exit(json_encode(["result" => null, "error" => "This email address has already been used."]));
    }

    $result = mysqli_query($open, "INSERT INTO `cp_users` (`name`, `surname`, `email`, `password`) VALUES ('".$name."', '".$surname."', '".$email."', '".$password."') ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
    }

    exit(json_encode(["result" => "Register Successfull", "error" => null]));
}
