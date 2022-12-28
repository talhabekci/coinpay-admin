<?php
error_reporting(E_ALL ^ E_DEPRECATED); //bchexdec fonkisyonundaki deprecated uyarısını almamak için koydum.
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

//0x5303a6604bdc36c83dd0f9b173ca415d4140374a => 0.08

$transfer["amount"] =  "10";
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

$signTransaction = eth_request("eth_signTransaction", [["from" => "0x5303a6604bdc36c83dd0f9b173ca415d4140374a", "to" => "0xd4AfeA4bf7cAeE0834fb7d60E28f1E886aA1D503", "data" => "0xa9059cbb000000000000000000000000d4AfeA4bf7cAeE0834fb7d60E28f1E886aA1D50300000000000000000000000000000000000000000000000000000000000003E8", "gas" => "0x" . dechex("42000"), "gasPrice" => $gasPrice["result"], "nonce" => $getTransactionCount["result"]]], 1);
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

//"0xa9059cbb" . str_pad(substr($address["address"], 2), 64, "0", STR_PAD_LEFT) . str_pad(bcdechex(bcmul(bcsub($transfer["amount"], $transfer["fee"], 18), "1000000000000000000")), 64, "0", STR_PAD_LEFT)