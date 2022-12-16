<?php

$result = mysqli_query($open, "SELECT * FROM `cp_users` WHERE `email` = '".$_SESSION["email"]."' ");
if ($result == false) {
    exit(json_encode(["result" => null, "error" => ["code" => null, "message" => "An error occurred while selecting data from database " . mysqli_error($open)]]));
}

?>
<div class="sidebar open">
    <div class="logo-details">
        <div class="logo_name">
            <a style="text-decoration:none; color:#F99A23;" href="#">
                <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/coinpay-white-svg.svg" width="72" height="26" alt="logo">
            </a>
        </div>
        <div class="org_name">legend plex</div>
        <i class="fa-regular fa-bars" id="btn"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/overview">
                <i class="fa-regular fa-chart-simple"></i>
                <span class="links_name">Overwiev</span>
            </a>
            <span class="tooltip">Overwiev</span>
        </li>
        <li class="submenu">
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/wallet">
                <i class="fa-regular fa-money-bills-simple"></i>
                <span class="links_name">Wallet</span>
            </a>
            <i class="fa-regular fa-chevron-down"></i>
            <span class="tooltip">Wallet</span>
            <ul style="display: none;">
                <li>
                    <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/wallet/bitcoin-wallet">
                        <i class="fa-brands fa-bitcoin"></i>
                        <!-- <div class="bg">
                            <img style="position:absolute;width:20px;height:20px;top:3px;left:3px;" src="../assets/img/btc.svg" alt="Bitcoin">
                        </div> -->
                        <span class="links_name">Bitcoin Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/wallet/ethereum-wallet">
                        <i class="fa-brands fa-ethereum"></i>
                        <!-- <div class="bg">
                            <img style="position:absolute;width:30px;height:30px;top:-2px;left:-2px;" src="../assets/css/eth.svg" alt="Ethereum">
                        </div> -->
                        <span class="links_name">Ethereum Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/wallet/tether-wallet">
                        <i class="fa-brands fa-tether">₮</i>
                        <span class="links_name">Tether Wallet</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/payments">
                <i class="fa-regular fa-wave-pulse"></i>
                <span class="links_name">Payments</span>
            </a>
            <span class="tooltip">Payments</span>
        </li>
        <li>
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/payment-tools">
                <i class="fa-regular fa-box-dollar"></i>
                <span class="links_name">Payment Tools</span>
            </a>
            <span class="tooltip">Payment Tools</span>
        </li>
        <li>
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/settings">
                <i class="fa-regular fa-gear"></i>
                <span class="links_name">Settings</span>
            </a>
            <span class="tooltip">Settings</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <img src="http://<?=$host_name["ip_address"]?>/coinpay-admin/assets/img/avatars/<?=$_SESSION["avatar"]?>" alt="profileImg">
                <div class="name">
                    <div class="name"> <?=$_SESSION["name"]?> <?=$_SESSION["surname"]?></div>
                </div>
            </div>
            <a href="http://<?=$host_name["ip_address"]?>/coinpay-admin/logout/">
                <i class="fa-regular fa-right-from-bracket" id="log_out"></i>
            </a>
        </li>
    </ul>
</div>
