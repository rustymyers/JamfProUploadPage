<?php
// Joshua Harvey <josh@macjeezy.com>
// November 2018
// GitHub: github.com/therealmacjeezy
// JamfNation: therealmacjeezy

include('globals.php');

libxml_use_internal_errors(true);

$get_data = callAPI('GET', ('https://' . $mdmhostname . '/JSSResource/categories'), false, $mdmapiusername, $mdmapipass);

$xml = simplexml_load_string($get_data);

$fileName = "CategoriesList.xml";

//file_put_contents($fileName, $get_data);

$xml->asXML($fileName);

?>
