<?php

if (isset($_POST["data"])) {
    echo json_encode(["ip_address" => "192.168.1.46", "error" => NULL]);
}

function getHost()
{

    $host_name = json_encode(["ip_address" => "192.168.1.46", "error" => NULL]);

    return json_decode($host_name, TRUE);
}
