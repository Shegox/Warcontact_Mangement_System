<?php
include_once  __DIR__ ."/../../php/crestLogIn.php";
include_once  __DIR__ ."/../../php/isAuth.php";
session_start();
if($_SESSION["auth"]["auth"]<2){throw new Exception("Not authorized");}
$id = $_SESSION["auth"]["charID"];


$characterID = addslashes($_POST["characterID"]);
$tag=(empty($_POST["tag"]))?"NULL":"'" . addslashes($_POST["tag"]) . "'";
$addedByCharacterID=$id;
$groupID = $_SESSION["auth"]["groupID"];

sql_write("UPDATE `hostilealts` SET tag=$tag WHERE characterID = $characterID AND groupID = $groupID");
