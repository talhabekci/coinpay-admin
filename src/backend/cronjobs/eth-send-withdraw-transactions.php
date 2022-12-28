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

//Ethereum Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'eth' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

while ($eth_withdraws =  mysqli_fetch_array($result)) {

    $transfer["fee"] = "0.000000000000000000";

    $getTransactionCount = eth_request("eth_getTransactionCount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "pending"], 1);
    echo "Get Transaction Count";
    echo "<pre>";
    echo json_encode($getTransactionCount, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $unlockAccount = eth_request("personal_unlockAccount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "root-password"], 1);
    echo "Unlock Account";
    echo "<pre>";
    echo json_encode($unlockAccount, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $gasPrice = eth_request("eth_gasPrice", [], 1);
    echo "Gas Price";
    echo "<pre>";
    echo json_encode($gasPrice, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "Gas Price Converted: " . $gasPrice["result"] = "0x" . bcdechex(bcmul(bchexdec($gasPrice["result"]), 25));
    echo "<hr>";

    $signTransaction = eth_request("eth_signTransaction", [["from" => "0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "to" => $eth_withdraws["address"], "value" => "0x" . bcdechex(bcmul(bcsub($eth_withdraws["amount"], $transfer["fee"], 18), "1000000000000000000")), "gas" => "0x" . dechex("21000"), "gasPrice" => $gasPrice["result"], "nonce" => $getTransactionCount["result"]]], 1);
    echo "Sign Transaction";
    echo "<pre>";
    echo json_encode($signTransaction, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $lockAccount = eth_request("personal_lockAccount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a"], 1);
    echo "Lock Account";
    echo "<pre>";
    echo json_encode($lockAccount, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $sendRawTransaction = eth_request("eth_sendRawTransaction", [$signTransaction["result"]["raw"]], 1);
    echo "Send Raw Transaction";
    echo "<pre>";
    echo json_encode($sendRawTransaction, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    // $result = mysqli_query($open, "INSERT INTO `cp_transactions` (`user_id`, `type`, `txid`, `order_id`, `currency`, `confirmation`, `status`, `is_wallet`) VALUES ('" . $_SESSION["user_id"] . "', '1', '" . $sendRawTransaction["result"] . "', NULL, 'eth', '0', '0', 'yes')");
    // if ($result === false) {
    //     exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data on database " . mysqli_error($open)]]));
    // }

    // $result = mysqli_query($open, "UPDATE `cp_withdraws` SET `status` = '2' WHERE `id` = '" . $eth_withdraws["id"] . "' ");
    // if ($result == FALSE) {
    //     exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    // }
}
