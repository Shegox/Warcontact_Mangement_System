<?php

include_once  __DIR__ ."/crest.php";

function getGroupId($auth_code)
{
    $charInfo = getCharInfo2($auth_code);
    return (getCharGroup($charInfo->id));

}

function getCharGroup($id)
{
    $url = "https://api.eveonline.com/eve/CharacterAffiliation.xml.aspx?ids=" . $id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
// Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Get the response and close the channel.
    $headers = Array(
        "Reddit: Shegox",
        "IGN: Shegox Gabriel"
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");

    $response = curl_exec($ch);
//print_r($response);
    curl_close($ch);

    $response = simplexml_load_string($response);
    /** @noinspection PhpUndefinedFieldInspection */
    $response = $response->result->rowset->row;
    if ($response["allianceID"] == 0) {
        return (int)$response["corporationID"];
    } else {
        return (int)$response["allianceID"];
    }
}

function getCharInfo2($auth_code)
{
    $charUrl = curl_get("https://crest-tq.eveonline.com/decode/", $auth_code)->character->href;
    $charInfo = curl_get($charUrl, $auth_code);
    return $charInfo;
}