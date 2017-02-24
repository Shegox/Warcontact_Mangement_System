<?php
include_once __DIR__ . '/../../php/constants.php';
session_set_cookie_params($GLOBALS["SESSION_TIMEOUT"]);
ini_set('session.gc_maxlifetime',$GLOBALS["SESSION_TIMEOUT"]);
session_start();

if (array_key_exists("auth",$_SESSION))
{
echo json_encode($_SESSION["auth"]);
}
else
{
	http_response_code(200);
}
