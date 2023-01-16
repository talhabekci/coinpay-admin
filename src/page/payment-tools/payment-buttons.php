<?php

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '" . $_SESSION["email"] . "' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

$n = mysqli_num_rows($result);

if ($n <= 0) {
    header("Location: http://" . $host_name["ip_address"] . "/coinpay-admin/login/");
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/sidebar/style.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/payment-tools/payment-tools.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/payment-tools/prism.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/css/payment-tools/payment-buttons.css">
    <link rel="stylesheet" href="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/sidebar/index.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/payment-tools/payment-tools.js"></script>
    <script src="http://<?= $host_name["ip_address"] ?>/coinpay-admin/assets/js/payment-tools/prism.js"></script>
    <title>CoinPay Payment Tools</title>
</head>

<body>
    <?php include("src/page/sidebar/sidebar.php"); ?>
    <div class="page">
        <div class="page_title">Payment Buttons</div>
        <div class="payment-tools">
            <div class="checkout-button">
                <div class="input-div">
                    <h3>Create a Checkout Button</h3>
                    <label for="">Default Price</label>
                    <div class="inp-default-price">
                        <input type="text" value="1" id="default-price">
                    </div>
                    <label id="order-id-label" for="">Order Id</label>
                    <div class="inp-order-id">
                        <input type="text" value="" placeholder="e.g. 102" id="order-id">
                    </div>
                </div>

                <div class="text1">
                    <p id="p1">This button is used to complete a sale on your website.</p>
                    <p id="p2">The merchant manages the shopping cart and collects the buyers' names and addresses if necessary.</p>
                    <input type="text" id="deneme" value="">
                </div>
            </div>
            <div class="code">
                <div class="generated-code">
                    <h3>Generated Code</h3>
                    <p>Select all of the HTML code below, then copy and paste it into your web page.</p>
                    <div class="in-code">
                        <pre class="language-html">
                            <code class="language-html">
&lt;form id="form" action="http://<?= $host_name["ip_address"] ?>/coinpay/select-currency/" method="post"&gt;
  &lt;input type="hidden" name="data" value=""&gt;
  &lt;input type="image" src="http://<?= $host_name["ip_address"] ?>/coinpay/assets/img/coinpay-logo.png" name="submit" style="width: 180px; height: 50px;" alt="CoinPay, the easy way to pay with bitcoins."&gt;
&lt;/form&gt;
                            </code>
                        </pre>
                    </div>
                    <div class="copy" data-clipboard-target=".in-code">
                        <i class="fa-regular fa-clipboard"></i>
                        <p>COPY CODE</p>
                    </div>
                    <div class="with-js"></div>
                </div>

                <div class="code-button">
                    <h3 id="preview-text">Preview</h3>
                    <form id="test-form" action="" method="post">
                        <input type="hidden" name="data" value="">
                        <input type="image" src="http://<?= $host_name["ip_address"] ?>/coinpay/assets/img/coinpay-logo.png" name="submit" style="width: 180px; height: 50px;" alt="CoinPay, the easy way to pay with bitcoins.">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    new ClipboardJS('div.copy');
    $(".copy").click(function() {
        $(".with-js").html("COPIED TO CLIPBOARD");

        $(".with-js").css("animation", "opacity 3s infinite");

        setTimeout(function() {
            $('.with-js').html("");
            $(".with-js").css("animation", "");
        }, 2900);

    });
</script>

</html>