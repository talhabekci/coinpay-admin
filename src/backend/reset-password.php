<?php

require 'config.php';

if (empty($_POST["key"] && $_POST["verification-code"])) {
    exit(json_encode(["result" => NULL, "error" => "Please enter verification code"]));
}

$code = mysqli_real_escape_string($open, $_POST["verification-code"]);
$key = mysqli_real_escape_string($open, $_POST["key"]);
$new_password = mysqli_real_escape_string($open, $_POST["new-password"]);
$retype_password = mysqli_real_escape_string($open, $_POST["retype-password"]);

$result = mysqli_query($open, "SELECT * FROM `cp_reset_password` WHERE `key` = '" . $key . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) == 0) {
    exit(json_encode(["result" => NULL, "error" => "An error occurred. Missig key!"]));
}

$user = mysqli_fetch_array($result);

if ($user["code"] != $code) {
    exit(json_encode(["result" => NULL, "error" => "Verification code mismatch!"]));
}

if ($new_password != $retype_password) {
    exit(json_encode(["result" => NULL, "error" => "Passwords does not match!"]));
}

$result = mysqli_query($open, "UPDATE `cp_users` SET `password` = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $user["user_id"] . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}

$result = mysqli_query($open, "UPDATE `cp_reset_password` SET `status` = '2' WHERE `key` = '" . $key . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}

exit(json_encode(["result" => "Password Reset Sucessfull.", "error" => null]));
