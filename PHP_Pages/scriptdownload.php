<?php
// Joshua Harvey <josh@macjeezy.com>
// November 2018
// GitHub: github.com/therealmacjeezy
// JamfNation: therealmacjeezy

include('globals.php');

$errors = []; // Store all foreseen and unforseen errors here
// File Extensions go here.
$fileExtensions = ['sh', 'py'];
$uploadDirectory = "/Scripts/";
$currentDir = getcwd();

libxml_use_internal_errors(true);

if (isset($_POST['download'])) {

    $scriptName = $_POST['jamf_script_name'];

    if (empty($scriptName)) {
        $errors[] = "The download option was selected, but the script to download was left blank. Please select a script to download and try again.";
    }

    if (empty($errors)) {
		$siteURL = urlencode($scriptName);
        $get_data = callAPI('GET', 'https://' . $mdmhostname . '/JSSResource/scripts/name/' . $siteURL , false, $mdmapiusername, $mdmapipass);

        $xml = simplexml_load_string($get_data);

        header("Content-Disposition: attachment; filename=\"$scriptName.sh\"");
        echo $xml->script_contents;
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "\n";
            echo "<a href=\"javascript:history.go(-1)\">Try Again</a>";
        }
    }
}
?>
