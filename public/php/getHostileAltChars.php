<?php
include_once  __DIR__ ."/../../php/crestLogIn.php";
include_once  __DIR__ ."/../../php/isAuth.php";
session_start();
if($_SESSION["auth"]["auth"]<2){throw new Exception("Not authorized");}
$id = $_SESSION["auth"]["charID"];
$groupID = $_SESSION["auth"]["groupID"];
$result = array();
//$myAlts = sql_read("SELECT COUNT(DISTINCT characterID) FROM `characters` WHERE `mainCharID` = '$id'")[0][0];
$temp = sql_read("SELECT DISTINCT hostilealts.characterName, hostilealts.characterID, characters.mainCharName, hostilealts.added, hostilealts.tag 
FROM hostilealts 
LEFT JOIN characters ON (hostilealts.addedBycharacterID = characters.mainCharID)
WHERE groupID = $groupID");
$result["items"] = $temp;
$result["charCount"] = (string)count($temp);
echo(json_encode($result));
