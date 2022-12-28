<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED); //bchexdec fonkisyonundaki deprecated uyarısını almamak için koydum.

require '../config.php';
require '../request.php';

//Bitcoin Send Withdraw
$result = mysqli_query($open, "SELECT * FROM `cp_withdraws` WHERE `status` = 0 AND `currency` = 'btc' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) == 0) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "No waiting bitcoin withdraw transactions"]]));
}

$output = [];
$id = [];

while ($btc_withdraws =  mysqli_fetch_array($result)) {

    if (isset($output[$btc_withdraws["address"]]) == FALSE) {
        $output[] = [$btc_withdraws["address"] => $btc_withdraws["amount"]];
        $id[] = $btc_withdraws["id"];
    }
}
echo json_encode($output);
echo "<hr>";

$createrawtransaction = request("createrawtransaction", [[], $output]);
echo "Create Raw Transaction = ";
echo "<br>";
echo "<pre>";
echo json_encode($createrawtransaction["result"], JSON_PRETTY_PRINT);
echo "</pre>";
echo "<hr>";

$fundrawtransaction = request("fundrawtransaction", [$createrawtransaction["result"]]);
echo "Fund Raw Transaction = ";
echo "<br>";
echo "<pre>";
echo json_encode($fundrawtransaction["result"], JSON_PRETTY_PRINT);
echo "</pre>";
echo "<hr>";

$decoderawtransaction = request("decoderawtransaction", [$fundrawtransaction["result"]["hex"]]);
echo "Decode Raw Transaction = ";
echo "<br>";
echo "<pre>";
echo json_encode($decoderawtransaction["result"], JSON_PRETTY_PRINT);
echo "</pre>";
echo "<hr>";

$privkey = [];
foreach ($decoderawtransaction["result"]["vin"] as $vin) {
    echo "Decode Raw Transaction = ";
    echo "<br>";
    echo "<pre>";
    echo json_encode($vin, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $gettransaction = request("gettransaction", [$vin["txid"]]);
    echo "Get Transaction = ";
    echo "<br>";
    echo "<pre>";
    echo json_encode($gettransaction["result"], JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $decoderawtransaction = request("decoderawtransaction", [$gettransaction["result"]["hex"]]);
    echo "Decode Raw Transaction = ";
    echo "<br>";
    echo "<pre>";
    echo json_encode($gettransaction["result"], JSON_PRETTY_PRINT);
    echo "</pre>";
    echo "<hr>";

    $dumpprivkey = request("dumpprivkey", [$decoderawtransaction["result"]["vout"][$vin["vout"]]["scriptPubKey"]["addresses"][0]]);
    $privkey[] = $dumpprivkey["result"];
}

echo "Privkey = ";
echo "<br>";
echo json_encode($privkey);
echo "<hr>";

$signrawtransactionwithkey = request("signrawtransactionwithkey", [$fundrawtransaction["result"]["hex"], $privkey]);
echo "Sign Raw Transaction With Key= ";
echo "<br>";
echo "<pre>";
echo json_encode($signrawtransactionwithkey["result"], JSON_PRETTY_PRINT);
echo "</pre>";
echo "<hr>";

$decoderawtransaction = request("decoderawtransaction", [$signrawtransactionwithkey["result"]["hex"]]);
echo "Decode Raw Transaction = ";
echo "<br>";
echo "<pre>";
echo json_encode($decoderawtransaction["result"]["txid"], JSON_PRETTY_PRINT);
echo "</pre>";
echo "<hr>";

$sendrawtransaction = request("sendrawtransaction", [$signrawtransactionwithkey["result"]["hex"]]);
echo "Send Raw Transaction = ";
echo "<br>";
echo "<pre>";
echo json_encode($decoderawtransaction["result"]["txid"], JSON_PRETTY_PRINT);
echo "</pre>";

$result = mysqli_query($open, "INSERT INTO `cp_transactions` (`user_id`, `type`, `txid`, `order_id`, `currency`, `confirmation`, `status`, `is_wallet`) VALUES ('" . $_SESSION["user_id"] . "', '1', '" . $decoderawtransaction["result"]["txid"] . "', NULL, 'btc', '0', '0', 'yes')");
if ($result === false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data on database " . mysqli_error($open)]]));
}

$result = mysqli_query($open, "UPDATE `cp_withdraws` SET `status` = '2' WHERE `id` = '" . $id . "' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
}
