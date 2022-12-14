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

    $result = mysqli_query($open, "INSERT INTO `cp_users` (`user_avatar`, `name`, `surname`, `email`, `password`) VALUES ('default_avatar.png', '".$name."', '".$surname."', '".$email."', '".$password."') ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
    }

    $last_id = mysqli_insert_id($open);

    $result = mysqli_query($open, "INSERT INTO `cp_balances` (`user_id`, `currency`, `amount`) VALUES ('".$last_id."', 'btc', '0'), ('".$last_id."', 'eth', '0'), ('".$last_id."', 'usdt', '0') ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
    }

    $_SESSION["btc_balance"] = 0;
    $_SESSION["eth_balance"] = 0;
    $_SESSION["usdt_balance"] = 0;

    exit(json_encode(["result" => "Register Successfull", "error" => null]));
}
