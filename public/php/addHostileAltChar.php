<?php
include_once  __DIR__ ."/../../php/crestLogIn.php";
include_once  __DIR__ ."/../../php/isAuth.php";
session_start();
if($_SESSION["auth"]["auth"]<2){throw new Exception("Not authorized");}
$id = $_SESSION["auth"]["charID"];

//$id = getCharInfo($_POST["auth_code"])->id;

$characterID = addslashes($_POST["characterID"]);
$characterName= addslashes($_POST["characterName"]);
$tag=addslashes($_POST["tag"]);
$addedByCharacterID=$id;
$groupID = $_SESSION["auth"]["groupID"];

sql_write("INSERT INTO `hostilealts` (`characterID`,`characterName`,`tag`,`addedByCharacterID`,`groupID`) VALUES ('$characterID','$characterName','$tag','$addedByCharacterID','$groupID')");
