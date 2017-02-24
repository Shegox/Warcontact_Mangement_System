<?php

function curl_get($url, $auth_code = NULL)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Get the response and close the channel.
    if (isset($auth_code)) {
        $headers = array(
            "Authorization: Bearer " . $auth_code
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $response = curl_exec($ch);
    if (curl_getinfo($ch) ["http_code"] != 200) {
        http_response_code(curl_getinfo($ch) ["http_code"]);
        echo "Error";
        echo " HTTP_CODE:" . curl_getinfo($ch) ["http_code"];
        print_r(curl_getinfo($ch));
        print_r($response);
    }
    curl_close($ch);
    $response = json_decode($response);
    return $response;
}
