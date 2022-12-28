<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED); //bchexdec fonkisyonundaki deprecated uyarısını almamak için koydum.

require '../config.php';
require '../request.php';

function bchexdec($hex)
{
    if (strlen($hex) == 1) {
        return hexdec($hex);
    } else {
        $remain = substr($hex, 0, -1);
        $last = substr($hex, -1);
        return bcadd(bcmul(16, bchexdec($remain)), hexdec($last));
    }
}

function bcdechex($dec)
{
    $last = bcmod($dec, 16);
    $remain = bcdiv(bcsub($dec, $last), 16);

    if ($remain == 0) {
        return dechex($last);
    } else {
        return bcdechex($remain) . dechex($last);
    }
}

//Tether Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'usdt' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// while ($usdt_withdraws =  mysqli_fetch_array($result)) {

// }
