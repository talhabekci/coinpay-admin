<?php

function request_to_url($custom_url) {
    if (!$init = curl_init()) {
        return ["result" => null, "error" => ["code" => null, "message" => "Initalize a cURL session"]];
    }

    $options = [
        CURLOPT_PROTOCOLS => CURLPROTO_HTTP,
        CURLOPT_URL => $custom_url,
        CURLOPT_PORT => "80",
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_RETURNTRANSFER => true,
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
