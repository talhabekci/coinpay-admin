<?php

error_reporting(E_ALL ^ E_DEPRECATED);//bchexdec fonkisyonundaki deprecated uyarısını almamak için koydum.

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

function bcdechex($dec) {
    $last = bcmod($dec, 16);
    $remain = bcdiv(bcsub($dec, $last), 16);

    if($remain == 0) {
        return dechex($last);
    } else {
        return bcdechex($remain).dechex($last);
    }
}

//Bitcoin Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'btc' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// $output = [];
// $id = [];
//
// while ($btc_withdraws =  mysqli_fetch_array($result)) {
//
//     if (isset($output[$btc_withdraws["address"]]) == FALSE ) {
//         $output[] = [$btc_withdraws["address"] => $btc_withdraws["amount"]];
//         $id[] = $btc_withdraws["id"];
//     }
// }
// echo json_encode($output);
// echo "<hr>";
//
// $createrawtransaction = request("createrawtransaction", [[], $output]);
// echo "Create Raw Transaction = ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($createrawtransaction["result"], JSON_PRETTY_PRINT);
// echo "</pre>";
// echo "<hr>";
//
// $fundrawtransaction = request("fundrawtransaction", [$createrawtransaction["result"]]);
// echo "Fund Raw Transaction = ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($fundrawtransaction["result"], JSON_PRETTY_PRINT);
// echo "</pre>";
// echo "<hr>";
//
// $decoderawtransaction = request("decoderawtransaction", [$fundrawtransaction["result"]["hex"]]);
// echo "Decode Raw Transaction = ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($decoderawtransaction["result"], JSON_PRETTY_PRINT);
// echo "</pre>";
// echo "<hr>";
//
// $privkey = [];
// foreach ($decoderawtransaction["result"]["vin"] as $vin) {
//     echo "Decode Raw Transaction = ";
//     echo "<br>";
//     echo "<pre>";
//     echo json_encode($vin, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $gettransaction = request("gettransaction", [$vin["txid"]]);
//     echo "Get Transaction = ";
//     echo "<br>";
//     echo "<pre>";
//     echo json_encode($gettransaction["result"], JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $decoderawtransaction = request("decoderawtransaction", [$gettransaction["result"]["hex"]]);
//     echo "Decode Raw Transaction = ";
//     echo "<br>";
//     echo "<pre>";
//     echo json_encode($gettransaction["result"], JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $dumpprivkey = request("dumpprivkey", [$decoderawtransaction["result"]["vout"][$vin["vout"]]["scriptPubKey"]["addresses"][0]]);
//     $privkey[] = $dumpprivkey["result"];
// }
//
// echo "Privkey = ";
// echo "<br>";
// echo json_encode($privkey);
// echo "<hr>";
//
// $signrawtransactionwithkey = request("signrawtransactionwithkey", [$fundrawtransaction["result"]["hex"], $privkey]);
// echo "Sign Raw Transaction With Key= ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($signrawtransactionwithkey["result"], JSON_PRETTY_PRINT);
// echo "</pre>";
// echo "<hr>";
//
// $decoderawtransaction = request("decoderawtransaction", [$signrawtransactionwithkey["result"]["hex"]]);
// echo "Decode Raw Transaction = ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($decoderawtransaction["result"]["txid"], JSON_PRETTY_PRINT);
// echo "</pre>";
// echo "<hr>";
//
// $sendrawtransaction = request("sendrawtransaction", [$signrawtransactionwithkey["result"]["hex"]]);
// echo "Send Raw Transaction = ";
// echo "<br>";
// echo "<pre>";
// echo json_encode($decoderawtransaction["result"]["txid"], JSON_PRETTY_PRINT);
// echo "</pre>";

// $result = mysqli_query($open, "INSERT INTO `cp_transactions` (`type`, `txid`, `order_id`, `currency`, `confirmation`, `status`, `is_wallet`) VALUES ('1', '".$decoderawtransaction["result"]["txid"]."', NULL, 'btc', '0', '0', 'yes')");
// if ($result === false) {
//    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data on database " . mysqli_error($open)]]));
// }

//Ethereum Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'eth' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// while ($eth_withdraws =  mysqli_fetch_array($result)) {
//
//     $transfer["fee"] = "0.000000000000000000";
//
//     $getTransactionCount = eth_request("eth_getTransactionCount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "pending"], 1);
//     echo "Get Transaction Count";
//     echo "<pre>";
//     echo json_encode($getTransactionCount, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $unlockAccount = eth_request("personal_unlockAccount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "root-password"], 1);
//     echo "Unlock Account";
//     echo "<pre>";
//     echo json_encode($unlockAccount, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $gasPrice = eth_request("eth_gasPrice", [], 1);
//     echo "Gas Price";
//     echo "<pre>";
//     echo json_encode($gasPrice, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "Gas Price Converted: " . $gasPrice["result"] = "0x" . bcdechex(bcmul(bchexdec($gasPrice["result"]), 25));
//     echo "<hr>";
//
//     $signTransaction = eth_request("eth_signTransaction", [["from" => "0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "to" => $eth_withdraws["address"], "value" => "0x" . bcdechex(bcmul(bcsub($eth_withdraws["amount"], $transfer["fee"], 18), "1000000000000000000")), "gas" => "0x" . dechex("21000"), "gasPrice" => $gasPrice["result"], "nonce" => $getTransactionCount["result"] ]], 1);
//     echo "Sign Transaction";
//     echo "<pre>";
//     echo json_encode($signTransaction, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $lockAccount = eth_request("personal_lockAccount", ["0x5303a6604bdc36c83dd0f9b173ca415d4140374a"], 1);
//     echo "Lock Account";
//     echo "<pre>";
//     echo json_encode($lockAccount, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $sendRawTransaction = eth_request("eth_sendRawTransaction", [$signTransaction["result"]["raw"]], 1);
//     echo "Send Raw Transaction";
//     echo "<pre>";
//     echo json_encode($sendRawTransaction, JSON_PRETTY_PRINT);
//     echo "</pre>";
//     echo "<hr>";
//
//     $result = mysqli_query($open, "INSERT INTO `cp_transactions` (`type`, `txid`, `order_id`, `currency`, `confirmation`, `status`, `is_wallet`) VALUES ('1', '".$sendRawTransaction["result"]."', NULL, 'eth', '0', '0', 'yes')");
//     if ($result === false) {
//        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data on database " . mysqli_error($open)]]));
//     }
//
// }

//Tether Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'usdt' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// while ($usdt_withdraws =  mysqli_fetch_array($result)) {

// }

?>
