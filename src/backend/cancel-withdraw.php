<?php

require 'config.php';

$withdraw_id = $_POST["withdraw_id"];

if ($_POST["action"] == "cancel") {
    $result = mysqli_query($open, "UPDATE `cp_withdraws` SET `status` = 1 WHERE `withdraw_id` = '".$withdraw_id."' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error ocurred while inserting data to database " . mysqli_error($open)]]));
    }

    exit(json_encode(["result" => "Withdraw Cancelled", "error" => null]));
}
