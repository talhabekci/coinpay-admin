<?php

require 'config.php';

if (empty($_POST["key"] && $_POST["verification-code"])) {
    exit(json_encode(["result" => NULL, "error" => "Please enter verification code"]));
}

$code = $_POST["verification-code"];
$key = $_POST["key"];

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `key` = '" . $key . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) == 0) {
    exit(json_encode(["result" => NULL, "error" => "An error occurred missig key!"]));
}

$user = mysqli_fetch_array($result);

if ($user["code"] != $code) {
    exit(json_encode(["result" => NULL, "error" => "Verification code mismatch!"]));
}

$result = mysqli_query($open, "UPDATE `cp_users` SET `status` = '2' WHERE `key` = '" . $key . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while updating data " . mysqli_error($open)]]));
}

exit(json_encode(["result" => "Verification Successfull.", "error" => null]));
