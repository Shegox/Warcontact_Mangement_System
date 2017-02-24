<?php
include_once  __DIR__ .'/crest.php';
include_once  __DIR__ .'/sql.php';
include_once  __DIR__ ."/functions.php";

set_time_limit(0);
// Timezone to EVE-Time
$custom_timeout = 20000;
date_default_timezone_set("Etc/GMT+0");

function getAmountOfWars()
{
    // jump to a not cached page to always get the newest warID
    $rand = rand(1000, 100000);
    $lastPage = curl_get("https://crest-tq.eveonline.com/wars/?page=$rand")->pageCount;
    $items = curl_get("https://crest-tq.eveonline.com/wars/?page=$lastPage")->items;
    print_r(end($items));
    return end($items)->id;
}

function getAllWarsFromEVE()
{
    $maxHandels = 15;
    $allWars = getAmountOfWars();
    $warID = sql_read("SELECT MAX(warID) FROM `allwars`") [0] [0] - 100;
    if ($warID < 1) {
        $warID = 1;
    }
    echo "Newest War in DB " . $warID;
    echo "<br>";
    echo "Newest War in Eve " . $allWars;
    echo "<br>";
    $mh = curl_multi_init();
    $handels = 0;
    $running = 1;
    $endtime = time() + $GLOBALS ["custom_timeout"];

    while ($running > 0) {
        if ($handels < $maxHandels && $warID <= $allWars && ($endtime > time())) {
            $ch = public_wars($warID);
            curl_multi_add_handle($mh, $ch);
            $handels++;
            $warID++;
        }
        curl_multi_exec($mh, $running);
        $info = curl_multi_info_read($mh);
        if ($info != FALSE) {
            //	echo " handels:" . $handels;
            $response = curl_multi_getcontent($info ["handle"]);
            $response = json_decode($response);
            //echo " HTTP_CODE:" . curl_getinfo ( $info ["handle"] ) ["http_code"];

            if (curl_getinfo($info ["handle"]) ["http_code"] != 200) {
                echo "Error";
                echo " HTTP_CODE:" . curl_getinfo($info ["handle"]) ["http_code"];
                print_r($response);
                $url = curl_getinfo($info ["handle"]) ["url"];
                $errorWarID = getWarIDFromURL($url);
                echo " ErrorWarID: " . $errorWarID;
                curl_multi_remove_handle($mh, $info ["handle"]);

                if (curl_getinfo($info ["handle"]) ["http_code"] != 404) {
                    $ch = public_wars($errorWarID);
                    curl_multi_add_handle($mh, $ch);
                    //		echo "<br>";
                } else {
                    $handels--;
                }
            } else {
                getWars($response);
                curl_multi_remove_handle($mh, $info ["handle"]);
                $handels--;
            }
        }
    }
    curl_multi_close($mh);
    echo "Done!";
    echo "Time needed: " . (time() - ($endtime - $GLOBALS ["custom_timeout"]));
    echo "<br>";
}

function getWars($war)
{
    $warArr = [];
    $warArr ["AgrGroupID"] = $war->aggressor->id;
    $warArr ["AgrGroupName"] = addslashes($war->aggressor->name);
    $warArr ["AgrGroupType"] = getGroupTypeFromHref($war->aggressor->href);
    $warArr ["DefGroupID"] = $war->defender->id;
    $warArr ["DefGroupName"] = addslashes($war->defender->name);
    $warArr ["DefGroupType"] = getGroupTypeFromHref($war->defender->href);
    $warArr ["status"] = getStatusFromInfo($war);
    $warArr ["warID"] = $war->id;
    //echo " WarID:" . $war->id;
    saveWars($warArr);
    // checks if allys exist and iterates over them
    // Only the defender can have allies, so the attributes stay the same for the attaker
    if ($war->allyCount > 0) {
        foreach ($war->allies as $allie) {
            $warArr ["DefGroupID"] = $allie->id;
            $warArr ["DefGroupName"] = addslashes($allie->name);
            $warArr ["DefGroupType"] = getGroupTypeFromHref($allie->href);
            saveWars($warArr);

        }
    }

}


