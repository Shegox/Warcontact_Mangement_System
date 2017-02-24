<?php
include_once  __DIR__ ."/../../php/userInfo.php";
include_once  __DIR__ ."/../../php/sql.php";
session_start();
$id = $_SESSION["auth"]["groupID"];
echo $id;
$del = addslashes($_POST["characterID"]);
echo $del;
sql_write("DELETE from `characters` where `characterID` = $del and `mainCharGroupID` = $id");