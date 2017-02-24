<?php
session_start();
include_once  __DIR__ ."/../../php/crestLogIn.php";
include_once  __DIR__ ."/../../php/isAuth.php";

if ($_GET["state"] == "logIn") {
    if (array_key_exists("code", $_GET)) {
        $auth = getTokensFromCode($_GET ["code"], "authorization_code");
        $auth_code = $auth->access_token;
        session_unset();
        $_SESSION["auth"] = isAuth($auth_code);
        header("Location: /");
    } else {
        header("Location: {$GLOBALS["LOGINURLwoSCOPE"]}&state=logIn");
    }
} else {
    if (array_key_exists("code", $_GET)) {
        getRefreshTokenAndSQL($_GET["code"], $_GET["state"]);
    } else {
        header("Location: {$GLOBALS["LOGINURL"]}&state={$_GET["state"]}");
    }
}