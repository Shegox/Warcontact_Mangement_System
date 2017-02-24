<?php

// used for session hijacking for debugging
session_start();
session_unset();

//$_SESSION["auth"]["auth"] = 2;
//$_SESSION["auth"]["charID"] = 1034380925;
//$_SESSION["auth"]["charName"] = "XXxTiggerxXX";
//$_SESSION["auth"]["groupID"] = 99002974;

//$_SESSION["auth"]["auth"] = 2;
//$_SESSION["auth"]["charID"] = 880954195;
//$_SESSION["auth"]["charName"] = "Threm";
//$_SESSION["auth"]["groupID"] = 99006906;

//$_SESSION["auth"]["auth"] = 2;
//$_SESSION["auth"]["charID"] = 92531837;
//$_SESSION["auth"]["charName"] = "Char Name";
//$_SESSION["auth"]["groupID"] = 99004507;


$_SESSION["auth"]["auth"] = 2;
$_SESSION["auth"]["charID"] = 96811478;
$_SESSION["auth"]["charName"] = "random bloodlust";
$_SESSION["auth"]["groupID"] = 99007145;


header("Location: /");
