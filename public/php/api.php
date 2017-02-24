<?php
include_once  __DIR__ ."/../../php/sql.php";

$sql = "SELECT * FROM `allwars` WHERE 1 ";

if (isset($_GET)) {

    if (array_key_exists("AgrID", $_GET)) {

        $AgrID = str_replace(" ", "','", $_GET ["AgrID"]);
        $sql .= "AND AgrGroupID IN ('$AgrID')";
    }

    if (array_key_exists("DefID", $_GET)) {

        $DefID = str_replace(" ", "','", $_GET ["DefID"]);
        $sql .= "AND DefGroupID IN ('$DefID')";
    }

    if (array_key_exists("ID", $_GET)) {

        $ID = str_replace(" ", "','", $_GET ["ID"]);
        $sql .= "AND (AgrGroupID IN ('$ID') OR DefGroupID IN ('$ID'))";
    }

    if (array_key_exists("status", $_GET)) {

        $status = str_replace(" ", "','", $_GET ["status"]);
        $sql .= "AND status IN ('$status')";
    }
    $sql .= " ORDER BY `allwars`.`WarID` ASC";
    if (array_key_exists("page", $_GET)) {
        $sql .= " LIMIT 200 OFFSET " . ($_GET ["page"] - 1) * 200;
        $object ["page"] = $_GET ["page"];
    } else {
        $sql .= " LIMIT 200 ";
        $object ["page"] = 1;
    }
} else {
    $sql .= " LIMIT 200 ";
    $object["page"] = 1;
}

$sql_count = str_replace("SELECT *", "SELECT COUNT(*)", $sql);

try {
    $response = sql_read($sql);
    $object["count"] = sql_read($sql_count)[0][0];
} catch (Exception $e) {
    echo $e;
    exit();
}

$object["groups"] = $response;
$response = (object)$object;
$response = json_encode($response);

echo $response;

