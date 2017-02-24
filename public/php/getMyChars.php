<?php
include_once __DIR__ .'/../../php/sql.php';
include_once __DIR__ ."/../../php/crestLogIn.php";
session_start();
$id = $_SESSION["auth"]["charID"];
//$id = getCharInfo($_GET["auth_code"])->id;
$result = array();
$myAlts = sql_read("SELECT COUNT(DISTINCT characterID) FROM `characters` WHERE `mainCharID` = '$id'")[0][0];
$temp = sql_read("SELECT characterName,changed,characterID FROM characters WHERE `mainCharID` = '$id'");
$result["items"] = $temp;
$result["myAlts"] = $myAlts;
echo(json_encode($result));
