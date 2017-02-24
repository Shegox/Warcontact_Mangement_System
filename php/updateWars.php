<?php
include_once  __DIR__ .'/crest.php';
include_once  __DIR__ .'/sql.php';
include_once  __DIR__ ."/functions.php";


set_time_limit(0);
// Timezone to EVE-Time
date_default_timezone_set("UTC");
function updateWars()
{
    $wars = getAllWarsFromDB();
    echo "done";
    $maxHandels = 15;
    $maxCount = count($wars);
    $mh = curl_multi_init();
    $handels = 0;
    $running = 1;
    $count = 0;
    while ($running > 0) {
        if ($handels < $maxHandels && $count < $maxCount) {
            print_r($wars [$count]);
            $ch = public_wars($wars [$count] ["WarID"]);
            curl_multi_add_handle($mh, $ch);
            $handels++;
            $count++;
        }
        curl_multi_exec($mh, $running);
        $info = curl_multi_info_read($mh);
        print_r($info);
        if ($info != FALSE) {
            //echo " handels:" . $handels;
            $response = curl_multi_getcontent($info ["handle"]);
            $response = json_decode($response);
            //echo " HTTP_CODE:" . curl_getinfo ( $info ["handle"] ) ["http_code"];
            if (curl_getinfo($info ["handle"]) ["http_code"] != 200) {
                echo "Error";

                print_r($response);
                $url = curl_getinfo($info ["handle"]) ["url"];
                curl_multi_remove_handle($mh, $info ["handle"]);
                $errorWarID = getWarIDFromURL($url);
                echo " ErrorWarID: " . $errorWarID;
                $ch = public_wars($errorWarID);
                curl_multi_add_handle($mh, $ch);
            } else {
                updateSingleWar($response);
                curl_multi_remove_handle($mh, $info ["handle"]);
                $handels--;
            }
            //echo "<br>";
        }
    }
    curl_multi_close($mh);
    echo "Done!";
}

function updateSingleWar($warInfo)
{
    $status = 0;
    if (dateToTimestemp($warInfo->timeStarted) <= time()) {
        $status = 1;
    }
    if (property_exists($warInfo, "timeFinished") == TRUE) {
        if (dateToTimestemp($warInfo->timeFinished) <= time()) {
            $status = 2;
        }
    }
    
    sql_write("UPDATE `allwars` SET `status`=$status WHERE `warID`='{$warInfo->id}';");
	updateAllies($warInfo);
}

function updateAllies($war)
{
    $warArr = [];
    $warArr ["AgrGroupID"] = $war->aggressor->id;
    $warArr ["AgrGroupName"] = addslashes($war->aggressor->name);
    $warArr ["AgrGroupType"] = getGroupTypeFromHref($war->aggressor->href);
    $warArr ["status"] = getStatusFromInfo($war);
    $warArr ["warID"] = $war->id;
$sql = "DELETE FROM `allwars` where `WarID` = '{$war->id}' AND `DefGroupID` !='{$war->defender->id}'";
print_r($sql);
	print_r(sql_write($sql));
    if ($war->allyCount > 0) {
        foreach ($war->allies as $allie) {
            $warArr ["DefGroupID"] = $allie->id;
            $warArr ["DefGroupName"] = addslashes($allie->name);
            $warArr ["DefGroupType"] = getGroupTypeFromHref($allie->href);
            saveWars($warArr);
        }
    }


}

