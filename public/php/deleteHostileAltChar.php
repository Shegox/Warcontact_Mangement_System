<?php
include_once  __DIR__ ."/../../php/crestLogIn.php";
include_once  __DIR__ ."/../../php/isAuth.php";
session_start();
if($_SESSION["auth"]["auth"]<2){throw new Exception("Not authorized");}
$id = $_SESSION["auth"]["charID"];
$groupID = $_SESSION["auth"]["groupID"];
//$id = getCharInfo($_POST["auth_code"])->id;
echo $id . "<-ID";
$del = addslashes($_POST["characterID"]);
echo $del;
sql_write("DELETE from `hostilealts` where `characterID` = $del and `groupID` = $groupID");
