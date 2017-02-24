<?php
include_once  __DIR__ .'/crest.php';
include_once  __DIR__ .'/sql.php';
include_once  __DIR__ .'/constants.php';
include_once  __DIR__ .'/crestLogIn.php';
function updateAllContacts()
{
    $alliances = sql_read("SELECT * FROM `group`");
    foreach ($alliances as $alliance) {
        UpdateContacts($alliance["groupID"]);
    }
}

function UpdateContacts($warGroup)
{
    $chars = sql_read("SELECT * FROM `characters` WHERE mainCharGroupID = $warGroup");
    echo "Updating chars!";
    echo "\n";
    $i = 1;
    foreach ($chars as $char) {
        $wars = sql_read("SELECT * FROM `allwars` WHERE (`status`=1 OR `status`=0) AND (`AgrGroupID` = $warGroup OR `DefGroupID` = $warGroup)");
        echo "Char $i {$char["characterName"]} of " . count($chars) . ". ";
        $i++;
        $auth_code = getTokensFromCode($char ["refreshToken"], "refresh_token")->access_token;
        $contacts = getAllContacts($char ["characterID"], $auth_code);
        if ($contacts == "") {
            continue;
        }
        foreach ($contacts as $contact) {
            // if standing from my program
            if (($contact->standing == $GLOBALS["STANDING_DEC"] || $contact->standing == $GLOBALS["STANDING_ACT"])) {
                $warKey = groupWarStatus($contact->contact->id, $wars);
                if ($warKey != -1) {
                    $war = $wars [$warKey];
                    switch ($war ["status"]) {
                        case 0 :
                            // war is declare, check if status is correct
                            if ($contact->standing != $GLOBALS["STANDING_DEC"]) {
                                $group = getEnemy($war, $warGroup);
                                print_r(curl_post_group($char ["characterID"], $auth_code, $group ["name"], $group ["id"], $group ["type"], $group ["standing"]));
                                // addContact
                            }
                            // else all right
                            break;
                        case 1 :
                            // war active, check if status is correct
                            if ($contact->standing != $GLOBALS["STANDING_ACT"]) {
                                $group = getEnemy($war, $warGroup);
                                print_r(curl_post_group($char ["characterID"], $auth_code, $group ["name"], $group ["id"], $group ["type"], $group ["standing"]));
                                // addContact
                            }
                            // else all right
                            break;
                    }
                    // deletes war from wars, so only non added wars are over after this stuff
                    unset ($wars [$warKey]);
                } else {
                    print_r(curl_delete($char ["characterID"], $auth_code, $contact->contact->id));
                    // delete contact, because it is not in the war list
                }
                // contact is not important for tool, because not withhin standing!
            }
        }
        foreach ($wars as $war) {
            $group = getEnemy($war, $warGroup);
            print_r(curl_post_group($char ["characterID"], $auth_code, $group ["name"], $group ["id"], $group ["type"], $group ["standing"]));
            // addding all remaing wars
        }
        echo "Done!";
        echo "\n";
    }
    echo "Done!";
    echo "\n";
}

function getEnemy($war, $warGroupId)
{
    $group = [];
    if ($war ["AgrGroupID"] == $warGroupId) {
        $group ["standing"] = getStanding($war ["status"]);
        $group ["type"] = groupType($war ["DefGroupType"]);
        $group ["name"] = $war ["DefGroupName"];
        $group ["id"] = $war ["DefGroupID"];
    } elseif (($war ["DefGroupID"] == $warGroupId)) {
        $group ["standing"] = getStanding($war ["status"]);
        $group ["type"] = groupType($war ["AgrGroupType"]);
        $group ["name"] = $war ["AgrGroupName"];
        $group ["id"] = $war ["AgrGroupID"];
    }
    return $group;
}

function getStanding($status)
{
    if ($status == 0) {
        return $GLOBALS["STANDING_DEC"];
    } else {
        return $GLOBALS["STANDING_ACT"];
    }
}

function groupWarStatus($searchGroup, $wars)
{
    $outKey = -1;
    // search in all active wars for a war against that group
    foreach ($wars as $key => $war) {
        if ($war ["AgrGroupID"] == $searchGroup || $war ["DefGroupID"] == $searchGroup) {
            //	echo "JA";
            $outKey = $key;
            break;
        }
    }
    // war does not exist
    return $outKey;
}

function getAllContacts($charID, $auth_code)
{
    $contacts = curl_get("https://crest-tq.eveonline.com/characters/$charID/contacts/", $auth_code);
    $pages = $contacts->pageCount;
    $contacts = $contacts->items;
    for ($a = 2; $a <= $pages; $a++) {
        $contacts = array_merge($contacts, curl_get("https://crest-tq.eveonline.com/characters/$charID/contacts/?page=$a", $auth_code)->items);
    }
    return $contacts;
}

/** @noinspection PhpInconsistentReturnPointsInspection
 * @param $groupType
 * @return string
 */
function groupType($groupType)
{
    if ($groupType == 32) {
        return "Alliance";
    } elseif ($groupType == 2) {
        return "Corporation";
    }
}

function curl_delete($charID, $auth_code, $groupID)
{
    $ch = curl_init();
    $url = "https://crest-tq.eveonline.com/characters/$charID/contacts/$groupID/";
    curl_setopt($ch, CURLOPT_URL, $url);
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Get the response and close the channel.
    $headers = array(
        "Authorization: Bearer " . $auth_code,
        "Content-type: json"
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    $response = curl_exec($ch);
    if (curl_getinfo($ch) ["http_code"] != 200) {
        echo "Error";
        echo " HTTP_CODE:" . curl_getinfo($ch) ["http_code"];
        print_r(curl_getinfo($ch));
        print_r($response);
    }

    curl_close($ch);
    $response = json_decode($response);
    return $response;
}

function curl_post_group($charID, $auth_code, $groupName, $groupID, $groupType, $standing)
{
    $ch = curl_init();
    $url = "https://crest-tq.eveonline.com/characters/$charID/contacts/";
    curl_setopt($ch, CURLOPT_URL, $url);
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Does not verify peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Get the response and close the channel.
    $headers = array(
        "Authorization: Bearer " . $auth_code,
        "Content-type: json"
    );
    // headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // Post options, for get comment out
    curl_setopt($ch, CURLOPT_POST, 1);
    $post ["standing"] = ( int )$standing;
    $post ["contactType"] = $groupType;
    $post ["contact"] = array();
    $post ["contact"] ["id_str"] = "" . $groupID;
    $post ["contact"] ["href"] = "https://public-crest.eveonline.com/" . strtolower($groupType) . "s/" . $groupID . "/";
    $post ["contact"] ["name"] = $groupName;
    $post ["contact"] ["id"] = ( int )$groupID;
    $post = json_encode($post, JSON_UNESCAPED_UNICODE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    $response = curl_exec($ch);
    if (curl_getinfo($ch) ["http_code"] != 201) {
        echo "Error";
        echo " HTTP_CODE:" . curl_getinfo($ch) ["http_code"];
        print_r(curl_getinfo($ch));
        print_r($response);
    }
    curl_close($ch);
    $response = json_decode($response);
    return $response;
}
