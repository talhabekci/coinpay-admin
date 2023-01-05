<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'config.php';
require 'host-name.php';
$host_name = getHost();

if (!empty($_POST["submit"] && $_POST["fname"] && $_POST["lname"] && $_POST["email"] && $_POST["password"])) {
    $name = mysqli_real_escape_string($open, $_POST["fname"]);
    $surname = mysqli_real_escape_string($open, $_POST["lname"]);
    $email = mysqli_real_escape_string($open, $_POST["email"]);
    $password = mysqli_real_escape_string($open, password_hash($_POST["password"], PASSWORD_DEFAULT));

    $result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $email . "' ");
    if ($result == false) {
        exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data " . mysqli_error($open)]]));
    }

    $n = mysqli_num_rows($result);

    if ($n > 0) {
        exit(json_encode(["result" => null, "error" => ["message" => "This email address has already been used."]]));
    }

    $key = hash("sha256", $email);
    $code = rand(100000, 999999);

    $mail = new PHPMailer(true);

    try {

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'iibtahim.bbekci@gmail.com';
        $mail->Password   = 'ijdpaentlbymmmvo';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;


        $mail->setFrom('iibtahim.bbekci@gmail.com', 'CoinPay');
        $mail->addAddress($email, $name);


        $mail->isHTML(true);
        $mail->Subject = 'Verify Email';
        $mail->Body    = '
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    div.container {
                        position: absolute;
                        z-index: 100;
                        top: 0px;
                        left: 0px;
                        width: 100%;
                        height: 100%;
                        backdrop-filter: blur(5px);
                        transition: all 1s ease;
                    }

                    div.container>div.modal {
                        border-radius: 15px;
                        position: relative;
                        display: block;
                        margin: 150px auto 0px auto;
                        width: 500px;
                        background-color: #fff;
                        box-shadow: 10px 10px 10px 10px rgba(0, 0, 0, 0.25);
                        transition: all 0.3s ease;
                    }

                    div.container>div.modal>div.header {
                        border-top-left-radius: 10px;
                        border-top-right-radius: 10px;
                        background-color: #F99A23;
                        padding: 15px;
                    }

                    div.container>div.modal>div.header>h1 {
                        margin-block-start: 0px;
                        margin-block-end: 0px;
                        font-weight: 500;
                        font-size: 24px;
                        font-weight: 600;
                        color: #FFF;
                    }

                    div.container>div.modal>div.content {
                        padding: 15px;
                    }

                    div.container>div.modal>div.content>div.content-text {
                        font-size: 20px;
                    }

                    div.container>div.modal>div.code {
                        text-align: center;
                        font-size: 30px;
                        font-weight: 600;
                        letter-spacing: 2;
                    }

                    div.container>div.modal>div.verify-button {
                        display: flex;
                        justify-content: flex-start;
                        border-top: 1px solid #0006;
                        padding: 15px;
                    }

                    div.container>div.modal>div.verify-button>a.verify-email {
                        text-decoration: none;
                        border-radius: 2.5px;
                        padding: 10px 20px;
                        background-color: #F99A23;
                        color: #FFF;
                        font-size: 18px;
                    }
                </style>
            </head>

            <body>
                <div class="container">
                    <div class="modal">
                        <div class="header">
                            <h1>Please verify your CoinPay email address</h1>
                        </div>
                        <div class="content">
                            <div class="content-text">Please complete the account setup by entering the code given below</div>
                        </div>
                        <div class="code">' . $code . '</div>
                        <div class="verify-button">
                            <a href="http://' . $host_name["ip_address"] . '/coinpay-admin/verify-email/' . $key . '" class="verify-email">Verify Email</a>
                        </div>
                    </div>
                </div>
            </body>

            </html>
            ';

        $mail->send();

        $result = mysqli_query($open, "INSERT INTO `cp_users` (`user_avatar`, `name`, `surname`, `email`, `password`, `key`, `code`, `status`) VALUES ('default_avatar.png', '" . $name . "', '" . $surname . "', '" . $email . "', '" . $password . "', '" . $key . "', '" . $code . "', '0') ");
        if ($result == false) {
            exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
        }

        $last_id = mysqli_insert_id($open);

        $result = mysqli_query($open, "INSERT INTO `cp_balances` (`user_id`, `currency`, `amount`) VALUES ('" . $last_id . "', 'btc', '0'), ('" . $last_id . "', 'eth', '0'), ('" . $last_id . "', 'usdt', '0') ");
        if ($result == false) {
            exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
        }

        $_SESSION["btc_balance"] = 0;
        $_SESSION["eth_balance"] = 0;
        $_SESSION["usdt_balance"] = 0;

        exit(json_encode(["result" => "Registration Successful. We have sent a confirmation code to your email address, please verify your email address", "error" => null]));
    } catch (Exception $e) {

        $result = mysqli_query($open, "INSERT INTO `cp_users` (`user_avatar`, `name`, `surname`, `email`, `password`, `key`, `code`, `status`, `error`) VALUES ('default_avatar.png', '" . $name . "', '" . $surname . "', '" . $email . "', '" . $password . "', '" . $key . "', '" . $code . "', '1', '" . $mail->ErrorInfo . "') ");
        if ($result == false) {
            exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
        }

        exit(json_encode(["result" => NULL, "error" => ["code" => NULL, "message" => "Verification email send failed. " . $mail->ErrorInfo]]));
    }
}
