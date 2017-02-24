<?php
include_once __DIR__ .'/../../php/sql.php';
include_once __DIR__ ."/../../php/userInfo.php";
include_once __DIR__ ."/../../php/crestLogIn.php";
session_start();
$id = $_SESSION["auth"]["groupID"];

$result = array();
$activeWars = sql_read("SELECT COUNT(DISTINCT WarID) FROM `allwars` WHERE (`AgrGroupID` = $id or `DefGroupID` = $id) and (`status` = 1)")[0][0];
$activeTargets = sql_read("SELECT COUNT(WarID) FROM `allwars` WHERE (`AgrGroupID` = $id or `DefGroupID` = $id) and (`status` = 1)")[0][0];
$declaredWars = sql_read("SELECT COUNT(DISTINCT WarID) FROM `allwars` WHERE (`AgrGroupID` = $id or `DefGroupID` = $id) and (`status` = 0)")[0][0];
$declaredTargets = sql_read("SELECT COUNT(DISTINCT WarID) FROM `allwars` WHERE (`AgrGroupID` = $id or `DefGroupID` = $id) and (`status` = 0)")[0][0];
$temp = sql_read("SELECT * FROM `allwars` WHERE (`AgrGroupID` = $id or `DefGroupID` = $id) and (`status` = 0 or `status` = 1)");
for ($i = 0; $i < count($temp); $i++) {
    if ($temp[$i]["AgrGroupID"] == $id) {
		$temp[$i]["TargetGroupName"] = $temp[$i]["DefGroupName"];
		$temp[$i]["TargetGroupID"] = $temp[$i]["DefGroupID"];
		$temp[$i]["TargetGroupType"] = $temp[$i]["DefGroupType"];	
    } else {
		$temp[$i]["TargetGroupName"] = $temp[$i]["AgrGroupName"];
		$temp[$i]["TargetGroupID"] = $temp[$i]["AgrGroupID"];
		$temp[$i]["TargetGroupType"] = $temp[$i]["AgrGroupType"];
    }
}
$result["items"] = $temp;
$result["activeWars"] = $activeWars;
$result["activeTargets"] = $activeTargets;
$result["declaredWars"] = $declaredWars;
$result["declaredTargets"] = $declaredTargets;
echo(json_encode($result));