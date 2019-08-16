<?php
// Joshua Harvey <josh@macjeezy.com>
// November 2018
// GitHub: github.com/therealmacjeezy
// JamfNation: therealmacjeezy

include('globals.php');

libxml_use_internal_errors(true);

$get_data = callAPI('GET', 'https://' . $mdmhostname . '/JSSResource/scripts', false, $mdmapiusername, $mdmapipass);

$xml = simplexml_load_string($get_data);

$fileName = "ScriptList.xml";

$xml->asXML($fileName);

?>