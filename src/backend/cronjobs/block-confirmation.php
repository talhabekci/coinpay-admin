<?php
//Cronjob
error_reporting(E_ALL ^ E_DEPRECATED);//bchexdec fonkisyonundaki deprecated uyarısını almamak için koydum.
session_start();

require '../config.php';
require '../request.php';

function bchexdec($hex) {
    if(strlen($hex) == 1) {
        return hexdec($hex);
    } else {
        $remain = substr($hex, 0, -1);
        $last = substr($hex, -1);
        return bcadd(bcmul(16, bchexdec($remain)), hexdec($last));
    }
}
//
// function bcdechex($dec) {
//     $last = bcmod($dec, 16);
//     $remain = bcdiv(bcsub($dec, $last), 16);
//
//     if($remain == 0) {
//         return dechex($last);
//     } else {
//         return bcdechex($remain).dechex($last);
//     }
// }

//Bitcoin Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id`  FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'btc'  ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

// foreach ($result as $txid) {
//
//     $gettransaction = request("gettransaction", [$txid["txid"]]);
//
//     $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$gettransaction["result"]["confirmations"]."' WHERE `txid` = '".$txid["txid"]."' ");
//     if ($result == FALSE) {
//         exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
//     }
//
//     if ($gettransaction["result"]["confirmations"] >= 6) {
//
//         $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$txid["txid"]."' ");
//         if ($result == FALSE) {
//             exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
//         }
//
//
//
//         $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
//         if ($result == FALSE) {
//             exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
//         }
//
//         $user_balance = mysqli_fetch_array($result);
//         // echo "User Balance: " . $user_balance["amount"] . "<br>";
//
//         $result = mysqli_query($open, "SELECT `net_amount`  FROM `cp_orders` WHERE `order_id` = '".$txid["order_id"]."' ");
//         if ($result == FALSE) {
//             exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
//         }
//
//         $order_amount = mysqli_fetch_array($result);
//         // echo "Order Amount: " . $order_amount["net_amount"] . "<br>";
//
//         $new_balance = $user_balance["amount"] + $order_amount["net_amount"];
//
//         $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
//         if ($result == FALSE) {
//             exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
//         }
//
//
//     }
//
// }


//Ethereum and Tether Block Confirmation
$result = mysqli_query($open, "SELECT `txid` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND (`currency` = 'eth' OR `currency` = 'usdt') ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $eth_txid) {

    $getTransactionByHash = eth_request("eth_getTransactionReceipt", ["0x5d8194ddb32f580e6ee73f892a066c0b31fcecdb19d0faf1463719fb7222a4e5"], 1);

    echo var_dump($getTransactionByHash) . "<hr>";
    echo bcdiv(bchexdec($getTransactionByHash["result"]["logs"]["0"]["data"]), "100", 2);
    exit;
    // echo "<pre>";
    // echo json_encode($getTransactionByHash, JSON_PRETTY_PRINT) . "<hr>";
    // echo "<pre>";

    $blockNumber = bchexdec($getTransactionByHash["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    // $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."' WHERE `txid` = '".$eth_txid["txid"]."' ");
    // if ($result == FALSE) {
    //     exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    // }
    //
    // $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$eth_txid["txid"]."' ");
    // if ($result == FALSE) {
    //     exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    // }


}
?>
