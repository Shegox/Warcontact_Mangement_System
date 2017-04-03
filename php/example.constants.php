<?php
//Crest application
$CLIENTID = "<CLIENT_ID>";
$SECRETKEY = "<SECRET_KEY>";
$CALLBACKURL = "<DOMAIN_NAME>/php/addChar.php";

//War Standings
$STANDING_HOSTILEALTS = -3;
$STANDING_DEC = -4;
$STANDING_ACT = -9;

//SQL server
$DATABASE = "war";
$USER = "<DB_USER>";
$PASSWORD = "<DB_PASSWORD>";

//Session
$SESSION_TIMEOUT = 100000000;

//EvE Login informations (do not change)
$BASICAUTH = base64_encode("$CLIENTID:$SECRETKEY");
$LOGINURL = "https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=$CALLBACKURL&client_id=$CLIENTID&scope=characterContactsWrite+characterContactsRead";
$LOGINURLwoSCOPE = "https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=$CALLBACKURL&client_id=$CLIENTID";
