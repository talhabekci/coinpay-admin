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

if (!isset($_POST['email'])) {
    exit(json_encode(["result" => NULL, "error" => "Email is required"]));
}

$email = mysqli_real_escape_string($open, $_POST['email']);

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $email . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

if (mysqli_num_rows($result) == 0) {
    exit(json_encode(["result" => null, "error" => "There is no account with this email address"]));
}

$user_details = mysqli_fetch_array($result);

$key = hash("sha256", $email . time());
$code = rand(100000, 999999);

$result = mysqli_query($open, "INSERT INTO `cp_reset_password` (`user_id`, `email`, `key`, `code`, `status`) VALUES ('" . $user_details["id"] . "', '" . $email . "', '" . $key . "', '" . $code . "', '0') ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while inserting data " . mysqli_error($open)]]));
}

$mail = new PHPMailer(true);

$mail->SMTPDebug = SMTP::DEBUG_OFF;
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'iibtahim.bbekci@gmail.com';
$mail->Password   = 'ijdpaentlbymmmvo';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port       = 465;


$mail->setFrom('iibtahim.bbekci@gmail.com', 'CoinPay');
$mail->addAddress($email);


$mail->isHTML(true);
$mail->Subject = 'Reset Your Password';
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
        
                div.container>div.modal>div.content>div.content-head {
                    font-size: 20px;
                    font-weight: bold;
                }

                div.container>div.modal>div.content>div.content-text {
                    font-size: 16px;
                    margin-top: 20px;
                }

                div.container>div.modal>div.code {
                    text-align: center;
                    font-size: 30px;
                    font-weight: 600;
                    letter-spacing: 2;
                }
        
                div.container>div.modal>div.reset-button {
                    display: flex;
                    justify-content: flex-start;
                    border-top: 1px solid #0006;
                    padding: 15px;
                }
        
                div.container>div.modal>div.reset-button>a.reset-password {
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
                        <h1>Reset Your Password</h1>
                    </div>
                    <div class="content">
                        <div class="content-head">Did you forget your password ?</div>
                        <div class="content-text">To create a new password, please follow the link below. Enter the code and your password manager ready.</div>
                    </div>
                    <div class="code">' . $code . '</div>
                    <div class="reset-button">
                        <a href="http://' . $host_name["ip_address"] . '/coinpay-admin/reset-password/' . $key . '" class="reset-password">Reset Password</a>
                    </div>
                </div>
            </div>
        </body>
        
        </html>
        ';

$mail->send();

exit(json_encode(["result" => "Password reset link has been sent to your email address.", "error" => null]));
