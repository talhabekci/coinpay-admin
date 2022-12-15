<?php

function getHost() {

    $host_name = [];

    if ($_SERVER['REMOTE_ADDR'] == "::1") {
        $host_name = "localhost";
    }else {
        $host_name = $_SERVER['REMOTE_ADDR'];
    }

    echo $host_name;

}

?>
