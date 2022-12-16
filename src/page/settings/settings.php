<?php

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://".$host_name["ip_address"]."/coinpay-admin/login/");
}

$user_info = mysqli_fetch_array($result);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/css/settings/settings.css">
    <link rel="stylesheet" href="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/sidebar/index.js"></script>
    <script src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/js/settings/settings.js"></script>
    <title>CoinPay Settings</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="error-message-box"></div>
        <div class="success-message-box"></div>

        <div class="page_title">Settings</div>
        <div class="account-information">
            <div class="settings-card">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="settings-panel">
                            <div class="settings-section">
                                <div class="settings-section-label">Profile Settings</div>
                                <form class="settings-form" method="post" enctype="multipart/form-data">
                                    <div class="settings-section-item">
                                        <div class="section-item-label">Profile Avatar</div>
                                        <div class="section-item-field">
                                            <input type="file" name="avatar" id="avatar" class="input avatar" value="<?=$user_info["user_avatar"]?>">
                                            <div class="error-message avatar"></div>
                                        </div>
                                    </div>
                                    <div class="settings-section-item">
                                        <div class="section-item-label">Display Name</div>
                                        <div class="section-item-field">
                                            <input type="text" name="fname" id="fname" class="input fname" value="<?=$user_info["name"]?>">
                                            <div class="error-message fname"></div>
                                        </div>
                                    </div>
                                    <div class="settings-section-item">
                                        <div class="section-item-label">Display Surname</div>
                                        <div class="section-item-field">
                                            <input type="text" name="lname" id="lname" class="input lname" value="<?=$user_info["surname"]?>">
                                            <div class="error-message lname"></div>
                                        </div>
                                    </div>
                                    <div class="settings-section-item">
                                        <div class="section-item-label">Email Address</div>
                                        <div class="section-item-field">
                                            <input type="text" name="email" id="email" class="input email" value="<?=$user_info["email"]?>">
                                            <div class="error-message email"></div>
                                        </div>
                                    </div>
                                    <div class="settings-section-item">
                                        <div class="section-item-label">Password</div>
                                        <div class="section-item-field">
                                            <input type="password" name="password" id="password" class="input password">
                                            <div class="error-message password"></div>
                                        </div>
                                    </div>
                                    <div class="submit-button">
                                        <button type="submit" name="submit" class="submit-signup">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
