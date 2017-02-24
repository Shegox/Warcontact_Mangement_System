<?php
include_once  __DIR__ ."/crest.php";
include_once  __DIR__ ."/sql.php";
include_once  __DIR__ ."/constants.php";
include_once  __DIR__ ."/userInfo.php";

function getRefreshTokenAndSQL($code, $main_auth_code)
{
    session_start();
    $error = false;
    try {
        $auth_infos = getTokensFromCode($code, "authorization_code");
        $refresh_token = $auth_infos->refresh_token;
        $auth_code = $auth_infos->access_token;
        $charInfo = getCharInfo($auth_code);
        $charID = $charInfo->id;
        $charName = addslashes($charInfo->name);
        $mainCharID = $_SESSION["auth"]["charID"];
        $mainCharName = addslashes($_SESSION["auth"]["charName"]);
        $mainCharGroupID = $_SESSION["auth"]["groupID"];
        if ($charID == NULL || $mainCharID == NULL) {
            throw new Exception ("Wrong Object! Error");
        }
    } catch (Exception $e) {

        echo 'Error! Please logIn again in both your Main-and Altchar';
        echo "<br>";
        echo "Please send a ingame mail with the following to Shegox Gabriel ingame <br>";
        echo "Error";
        exit ();
    }
    if ($error == false) {
        sql_write("INSERT INTO `characters`(`mainCharID`,`mainCharName`,`mainCharGroupID`,`characterID`, `characterName`, `refreshToken`) VALUES ('$mainCharID','$mainCharName','$mainCharGroupID','$charID','$charName','$refresh_token')");
        sql_write("UPDATE `characters` SET `refreshToken`='$refresh_token' WHERE `characterID`='$charID';");
        sql_write("UPDATE `characters` SET `changed`=NOW() WHERE `characterID`='$charID';");
        echo "You are added/updated to the watchlist tool. To remove your char please delete this application from your list. You can now close this window.";
        header("Location: /#access_token=$main_auth_code");
        exit ();
    }
}

function getCharInfo($auth_code)
{
    $charUrl = curl_get("https://crest-tq.eveonline.com/decode/", $auth_code)->character->href;
    $charInfo = curl_get($charUrl, $auth_code);
    return $charInfo;
}

function getTokensFromCode($code, $grant_type)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://login-tq.eveonline.com/oauth/token/");
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POST, 1);
    $headers = array(
        "Authorization: Basic {$GLOBALS["BASICAUTH"]}",
        "Content-type: application/x-www-form-urlencoded"
    );
    // headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $post ["grant_type"] = "authorization_code";
    $post ["code"] = $code;
    // grant_type = authorization_code ; initial request
    // grant_type = refresh_token; for request with refresh_token
    if ($grant_type == "refresh_token") {
        $parameter = "refresh_token";
    } elseif ($grant_type == "authorization_code") {
        $parameter = "code";
    }
    /** @noinspection PhpUndefinedVariableInspection */
    $post = "grant_type=$grant_type&$parameter=$code";
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);
    return $response;
}

