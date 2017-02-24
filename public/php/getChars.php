<?php
include_once  __DIR__ ."/../../php/sql.php";
include_once  __DIR__ ."/../../php/userInfo.php";
session_start();
$result = array();
//$groupID = getGroupId($_GET["auth_code"]);
$groupID = $_SESSION["auth"]["groupID"];
$mainchars = sql_read("SELECT COUNT(DISTINCT mainCharID) FROM `characters` WHERE `mainCharGroupID` = '$groupID'")[0][0];
$altchars = sql_read("SELECT COUNT(DISTINCT characterID) FROM `characters` WHERE `mainCharGroupID` = '$groupID'")[0][0];
$temp = sql_read("SELECT characterName,changed,characterID,mainCharID,mainCharName FROM characters WHERE `mainCharGroupID` = '$groupID'");

$result["items"] = $temp;
$result["mainchars"] = $mainchars;
$result["altchars"] = $altchars;
echo(json_encode($result));
