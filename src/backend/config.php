<?php

$open = mysqli_connect("localhost", "root", "root-password", "coin-pay");
if ($open == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while opening connection" . mysqli_connect_error()]]));
}

?>
