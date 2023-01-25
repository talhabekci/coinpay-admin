<?php

function request($method, $params)
{
    if (!$init = curl_init()) {
        return ["result" => null, "error" => ["code" => null, "message" => "Initalize a cURL session"]];
    }

    $options = [
        CURLOPT_PROTOCOLS => CURLPROTO_HTTP,
        CURLOPT_URL => "localhost",
        CURLOPT_PORT => 18332,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => "__cookie__:d184622a471afd5ca19de9b8a79373fd62ff9a6d17f860ae7f24ff42531ca21b",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["method" => $method, "params" => $params])
    ];

    if (!curl_setopt_array($init, $options)) {
        return ["result" => null, "error" => ["code" => null, "message" => "Set multiple options for a cURL transfer"]];
    }

    if (!$exec = curl_exec($init)) {
        echo curl_error($init);
        return ["result" => null, "error" => ["code" => null, "message" => "Perform a cURL session"]];
    }
    curl_close($init);
    return json_decode($exec, true);
}

function eth_request($method, $params, $id)
{
    if (!$init = curl_init()) {
        return ["result" => null, "error" => ["code" => null, "message" => "Initalize a cURL session"]];
    }

    $options = [
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_URL => "https://goerli.infura.io/v3/4b8eb8eda4d146519519cf568db26ea2",
        //CURLOPT_PORT => 8545,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["method" => $method, "params" => $params, "id" => $id])
    ];

    if (!curl_setopt_array($init, $options)) {
        return ["result" => null, "error" => ["code" => null, "message" => "Set multiple options for a cURL transfer"]];
    }

    if (!$exec = curl_exec($init)) {
        echo curl_error($init);
        return ["result" => null, "error" => ["code" => null, "message" => "Perform a cURL session"]];
    }
    curl_close($init);
    return json_decode($exec, true);
}
