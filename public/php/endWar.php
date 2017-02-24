<?php
include_once __DIR__ . "/../../php/userInfo.php";
include_once  __DIR__ ."/../../php/sql.php";
session_start();
$auth = $_SESSION["auth"]["auth"];

if ($auth >= 2)
{
$warID = addslashes($_POST["warID"]);
echo $del;
    sql_write("UPDATE `allwars` SET `status`=2 WHERE `warID`='$warID';");
}
else
{	http_response_code(401);
}