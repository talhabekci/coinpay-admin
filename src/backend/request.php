<?php

function request($method, $params) {
    if (!$init = curl_init()) {
        return ["result" => null, "error" => ["code" => null, "message" => "Initalize a cURL session"]];
    }

    $options = [
        CURLOPT_PROTOCOLS => CURLPROTO_HTTP,
        CURLOPT_URL => "localhost",
        CURLOPT_PORT => 18332,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => "__cookie__:9cf5a23f01f425f8b01e656b6bb65879994b73038457c5cea694b0510e1260ce",
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

function eth_request($method, $params, $id) {
    if (!$init = curl_init()) {
        return ["result" => null, "error" => ["code" => null, "message" => "Initalize a cURL session"]];
    }

    $options = [
        CURLOPT_PROTOCOLS => CURLPROTO_HTTP,
        CURLOPT_URL => "192.168.1.90",
        CURLOPT_PORT => 8545,
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
