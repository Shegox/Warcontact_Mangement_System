#!/usr/bin/php
<?php
include_once  __DIR__ ."/updateWars.php";
include_once  __DIR__ ."/updateContact.php";
include_once  __DIR__ ."/getAllWars.php";
getAllWarsFromEVE();
updateWars();
UpdateAllContacts();
?>