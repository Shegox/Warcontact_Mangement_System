<?php
include_once  __DIR__ .'/../../php/sql.php';
include_once  __DIR__ ."/../../php/crestLogIn.php";
session_start();
$id = $_SESSION["auth"]["charID"];
echo $id . "<-ID";
$del = addslashes($_POST["characterID"]);
echo $del;
sql_write("DELETE from `characters` where `characterID` = $del and `mainCharID` = $id");