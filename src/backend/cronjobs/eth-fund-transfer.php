<?php
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

$admin_address = "0x5303a6604bdc36c83dd0f9b173ca415d4140374a"; //Bütün bakiyler bu hesapta toplanacak;

$result = mysqli_query($open, "SELECT * FROM `cp_addresses` WHERE `currency` = 'eth' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

$gasPrice_hex = eth_request("eth_gasPrice", [], 1);
$gasPrice_dec = hexdec($gasPrice_hex["result"]);
$gasPrice = bcdiv($gasPrice_dec, 1000000000000000000, 18);
$fee = number_format($gasPrice * 21000, 18);

foreach ($result as $addresses) {

    $balance_hex = eth_request("eth_getBalance", [$addresses["address"], "latest"], 1);

    if ($balance_hex["result"] != "0x0") {

        $balance_dec = hexdec($balance_hex["result"]);
        $balance = bcdiv($balance_dec, 1000000000000000000, 18);

        $amount = number_format($balance - $fee, 18);
        $amount_dec = bcmul($amount, 1000000000000000000);
        $amount_hex = dechex($amount_dec);


        $eth_sendTransaction = eth_request("eth_sendTransaction", [["from" => $addresses["address"], "to" => "0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "value" => "0x" . $amount_hex]], 1);
        echo json_encode($eth_sendTransaction) . "<br>";
    }
}


// $balance_hex = eth_request("personal_listAccounts", [], 1);

// foreach ($balance_hex["result"] as $addresses) {
//     echo $addresses . "<br>";
// }
