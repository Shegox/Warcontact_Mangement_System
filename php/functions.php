<?php
function getWarIDFromURL($url)
{
    return explode("/", $url) [4];
}

function dateToTimestemp($date)
{
    $date = date_create_from_format('Y-m-d?H:i:s', $date);
    return date_timestamp_get($date);
}

function getAllWarsFromDB()
{
    return sql_read("SELECT `WarID` FROM `allwars` WHERE `status` = 1 OR `status` = 0 GROUP BY `WarID`");
}

function saveWars($war)
{
    $sql = "INSERT INTO `allwars` (`warID`, `AgrGroupID`, `AgrGroupName`, `AgrGroupType`, `DefGroupID`, `DefGroupName`, `DefGroupType`, `status`) VALUES ('$war[warID]', '$war[AgrGroupID]', '$war[AgrGroupName]', '$war[AgrGroupType]','$war[DefGroupID]', '$war[DefGroupName]', '$war[DefGroupType]','$war[status]')";
    sql_write($sql);
}

// return handle for multi curl with a request for that specific war
function public_wars($warID)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    $headers = array(
        "Cache-Control: no-cache"
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, "https://crest-tq.eveonline.com/wars/" . $warID . "/");
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    return $ch;
}

function getGroupTypeFromHref($href)
{
    if (strpos($href, "alliances") !== FALSE) {
        return 32;
    } else {
        return 2;
    }
}

function getStatusFromInfo($warInfo)
{
    $status = 0;
    if (dateToTimestemp($warInfo->timeStarted) <= time()) {
        //echo "Status = 1";
        $status = 1;
    }
    if (property_exists($warInfo, "timeFinished") == TRUE) {
        if (dateToTimestemp($warInfo->timeFinished) <= time()) {
            $status = 2;
        }
    }
    return $status;
}
