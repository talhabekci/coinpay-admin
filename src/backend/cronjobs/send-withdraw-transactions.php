<?php

require '../config.php';
require '../request.php';

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

// $result = mysqli_query($open, "INSERT INTO `cp_transactions` (`type`, `txid`, `order_id`, `currency`, `confirmation`, `status`, `is_wallet`) VALUES ('0', '".$decoderawtransaction["result"]["txid"]."', NULL, 'btc', NULL, '0', 'yes')");
// if ($result === false) {
//    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data on database " . mysqli_error($open)]]));
// }

//Ethereum Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'eth' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// while ($eth_withdraws =  mysqli_fetch_array($result)) {

// }

//Tether Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'usdt' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

// while ($usdt_withdraws =  mysqli_fetch_array($result)) {

// }

?>
