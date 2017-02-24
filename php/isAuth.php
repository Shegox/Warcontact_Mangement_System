<?php
include_once  __DIR__ ."/sql.php";
include_once  __DIR__ ."/userInfo.php";
include_once  __DIR__ ."/crestLogIn.php";

function isAuth($auth_code)
{
    $groupID = getGroupId($auth_code);
    $charID = getCharInfo($auth_code)->id;
    $charName = getCharInfo($auth_code)->name;
    $auth_infos = [];
    $auth_infos["groupID"] = $groupID;
    $auth_infos["charID"] = $charID;
    $auth_infos["charName"] = $charName;
    $auth = 0;
    if ($groupID != 0 && $charID != 0) {
        $admin = sql_read("SELECT * FROM `admin` Where `characterID` = '$charID'") [0] [0];
        $group = sql_read("SELECT * FROM `group` Where `groupID` = '$groupID'") [0] [0];
        if ($groupID == $group) {
            $auth = 1;
            if ($admin == $charID) {
                $auth = 2;
            }
        }
    } else {
        $auth = -1;
        // echo "Auth token invalid!";
    }
    $auth_infos["auth"] = $auth;
    return $auth_infos;
}