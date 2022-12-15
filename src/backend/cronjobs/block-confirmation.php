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
$result = mysqli_query($open, "SELECT `txid`, `order_id`  FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'btc' AND `is_wallet` = 'no'  ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $txid) {

    $gettransaction = request("gettransaction", [$txid["txid"]]);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$gettransaction["result"]["confirmations"]."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($gettransaction["result"]["confirmations"] >= 6) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        foreach ($gettransaction["result"]["details"] as $details) {

            if ($details["category"] == "receive") {

                $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $user_balance = mysqli_fetch_array($result);
                // echo "User Balance: " . $user_balance["amount"] . "<br>";

                $result = mysqli_query($open, "SELECT `net_amount`  FROM `cp_orders` WHERE `order_id` = '".$txid["order_id"]."' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $order_amount = mysqli_fetch_array($result);
                // echo "Order Amount: " . $order_amount["net_amount"] . "<br>";

                $new_balance = $user_balance["amount"] + $order_amount["net_amount"];

                $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
                }

                $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $session_balance = mysqli_fetch_array($result);
                $_SESSION["btc_balance"] = $session_balance["amount"];

            }

        }

    }

}

//Bitcoin Wallet Depsoit Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id`  FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'btc' AND `is_wallet` = 'yes'  ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $deposit_txid) {

    $gettransaction = request("gettransaction", [$deposit_txid["txid"]]);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$gettransaction["result"]["confirmations"]."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$deposit_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($gettransaction["result"]["confirmations"] >= 6) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$deposit_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        foreach ($gettransaction["result"]["details"] as $details) {

            if ($details["category"] == "receive") {

                $deposit_amount = 0;

                $result = mysqli_query($open, "SELECT `address`  FROM `cp_addresses` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $user_address = mysqli_fetch_array($result);

                if ($details["address"] == $user_address["address"]) {

                    $deposit_amount = $details["amount"];

                }

                $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $user_balance = mysqli_fetch_array($result);
                // echo "User Balance: " . $user_balance["amount"] . "<br>";

                $new_deposit_balance = $user_balance["amount"] + $deposit_amount;
                // echo "New Balance: " . number_format($new_balance, 8, '.');

                $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".number_format($new_deposit_balance, 8, '.')."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
                }

                $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'btc' ");
                if ($result == FALSE) {
                    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
                }

                $session_balance = mysqli_fetch_array($result);
                $_SESSION["btc_balance"] = $session_balance["amount"];

            }

        }

    }

}


// Ethereum Receive Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'eth' AND `type` = 0 AND `is_wallet` = 'no' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $eth_txid) {

    $getTransactionReceipt = eth_request("eth_getTransactionReceipt", [$eth_txid["txid"]], 1);

    $blockNumber = bchexdec($getTransactionReceipt["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$eth_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$eth_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $user_eth_balance = mysqli_fetch_array($result);
        // echo "User Balance: " . $user_eth_balance["amount"] . "<br>";

        $result = mysqli_query($open, "SELECT `net_amount`  FROM `cp_orders` WHERE `order_id` = '".$eth_txid["order_id"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $eth_order_amount = mysqli_fetch_array($result);
        // echo "Order Amount: " . $eth_order_amount["net_amount"] . "<br>";

        $new_eth_balance = $user_eth_balance["amount"] + $eth_order_amount["net_amount"];

        $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_eth_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth ' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $session_eth_balance = mysqli_fetch_array($result);
        $_SESSION["eth_balance"] = $session_eth_balance["amount"];

    }


}

// Ethereum Wallet Deposit Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'eth' AND `type` = 0 AND `is_wallet` = 'yes' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $eth_deposit_txid) {

    $getTransactionByHash = eth_request("eth_getTransactionByHash", [$eth_deposit_txid["txid"]], 1);

    $blockNumber = bchexdec($getTransactionByHash["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$eth_deposit_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$eth_deposit_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $user_eth_balance = mysqli_fetch_array($result);
        // echo "User Balance: " . $user_eth_balance["amount"] . "<br>";

        $eth_deposit_amount = bcdiv(hexdec($getTransactionByHash["result"]["value"]), "1000000000000000000", 8);

        $new_eth_deposit_balance = $user_eth_balance["amount"] + $eth_deposit_amount;
        // echo "New Balance: " . $new_eth_balance;
        $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_eth_deposit_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth ' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'eth' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $session_eth_balance = mysqli_fetch_array($result);
        $_SESSION["eth_balance"] = $session_eth_balance["amount"];

    }


}

//Ethereum Wihdraw Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'eth' AND `type` = 1 ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $eth_send_txid) {

    $getTransactionReceipt = eth_request("eth_getTransactionReceipt", [$eth_send_txid["txid"]], 1);

    $blockNumber = bchexdec($getTransactionReceipt["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."' WHERE `txid` = '".$eth_send_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$eth_send_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

    }

}



//Tether Receive Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'usdt' AND `type` = 0 AND `is_wallet` = 'no' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $usdt_txid) {

    $getTransactionReceipt = eth_request("eth_getTransactionReceipt", [$usdt_txid["txid"]], 1);

    $blockNumber = bchexdec($getTransactionReceipt["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$usdt_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$usdt_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $user_usdt_balance = mysqli_fetch_array($result);
        // echo "User Balance: " . $user_usdt_balance["amount"] . "<br>";

        $result = mysqli_query($open, "SELECT `net_amount`  FROM `cp_orders` WHERE `order_id` = '".$usdt_txid["order_id"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $usdt_order_amount = mysqli_fetch_array($result);
        // echo "Order Amount: " . $usdt_order_amount["net_amount"] . "<br>";

        $new_usdt_balance = $user_usdt_balance["amount"] + $usdt_order_amount["net_amount"];

        $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_usdt_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt ' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $session_usdt_balance = mysqli_fetch_array($result);
        $_SESSION["usdt_balance"] = $session_usdt_balance["amount"];

    }

}

// Tether Wallet Deposit Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'usdt' AND `type` = 0 AND `is_wallet` = 'yes' ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $usdt_deposit_txid) {

    $eth_getTransactionReceipt = eth_request("eth_getTransactionReceipt", [$usdt_deposit_txid["txid"]], 1);

    $blockNumber = bchexdec($eth_getTransactionReceipt["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."', `user_id` = '".$_SESSION["user_id"]."' WHERE `txid` = '".$usdt_deposit_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$usdt_deposit_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $user_usdt_balance = mysqli_fetch_array($result);
        echo "User Balance: " . $user_usdt_balance["amount"] . "<br>";

        $usdt_deposit_amount = bcdiv(hexdec($eth_getTransactionReceipt["result"]["logs"]["0"]["data"]), "100", 8);

        $new_usdt_deposit_balance = $user_usdt_balance["amount"] + $usdt_deposit_amount;

        $result = mysqli_query($open, "UPDATE `cp_balances` SET `amount` = '".$new_usdt_deposit_balance."' WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt ' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

        $result = mysqli_query($open, "SELECT `amount`  FROM `cp_balances` WHERE `user_id` = '".$_SESSION["user_id"]."' AND `currency` = 'usdt' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
        }

        $session_usdt_balance = mysqli_fetch_array($result);
        $_SESSION["usdt_balance"] = $session_usdt_balance["amount"];

    }

}

// //Tether Send Block Confirmation
$result = mysqli_query($open, "SELECT `txid`, `order_id` FROM `cp_transactions` WHERE `confirmation` < 6 AND `status` = 0 AND `currency` = 'usdt' AND `type` = 1 ");
if ($result == FALSE) {
    exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while selecting data" . mysqli_error($open)]]));
}

foreach ($result as $usdt_send_txid) {

    $getTransactionReceipt = eth_request("eth_getTransactionReceipt", [$usdt_send_txid["txid"]], 1);

    $blockNumber = bchexdec($getTransactionReceipt["result"]["blockNumber"]);


    $ethBlockNumber = eth_request("eth_blockNumber", [], 1);
    $currentBlockNumber = bchexdec($ethBlockNumber["result"]);

    $blockConfirmation = bcsub($currentBlockNumber, $blockNumber, 0);

    $result = mysqli_query($open, "UPDATE `cp_transactions` SET `confirmation` = '".$blockConfirmation."' WHERE `txid` = '".$usdt_send_txid["txid"]."' ");
    if ($result == FALSE) {
        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
    }

    if ($blockConfirmation > 12) {

        $result = mysqli_query($open, "UPDATE `cp_transactions` SET `status` = 2 WHERE `txid` = '".$usdt_send_txid["txid"]."' ");
        if ($result == FALSE) {
            exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "An error ocurred while updating data " . mysqli_error($open)]]));
        }

    }

}
?>
