<?php
session_start();

session_destroy();

header("Location: http://".$host_name["ip_address"]."/coinpay-admin/login/");
