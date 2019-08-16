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

if (isset($_POST['submit'])) {

    $siteName = $_POST['jamf_site'];
    $fileName = $_FILES['jamf_script']['name'];
    $fileSize = $_FILES['jamf_script']['size'];
    $fileTmpName  = $_FILES['jamf_script']['tmp_name'];
    $fileType = $_FILES['jamf_script']['type'];

    if (empty($siteName)) {
        $errors[] = "No Site was selected. Please select a site and try again";
    }

    //$fileExtension = strtolower(end(explode('.',$fileName)));
    //if (! in_array($fileExtension, $fileExtensions)) {
    //    $errors[] = "This file extension is not allowed. Please upload a valid file type (.sh, .py).";
    //}

    // This limits the size of the upload to 2.5 MB via bytes
    if ($fileSize > 2500000) {
        $errors[] = "This file is more than 2.5 MB limit. Please adjust the size or contact helpdesk@it.com for help.";
    }

    if (empty($errors)) {
        $scriptName = $siteName . "-" . basename($fileName);
		
        $uploadPath = $currentDir . $uploadDirectory . $scriptName;
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
		$scriptContent = file_get_contents($uploadPath);
		//$siteURL = urlencode($scriptName);
		$updateXML = "<script><name>$scriptName</name><category>Uploads</category><filename>$scriptName</filename><info/><notes/><priority>After</priority><script_contents>$scriptContent</script_contents></script>";


        if ($didUpload) {
			$get_data = callAPI('POST', 'https://' . $mdmhostname . '/JSSResource/scripts/id/0', $updateXML, $mdmapiusername, $mdmapipass);
            header('Refresh:0; url=index.html');
            echo "<script type='text/javascript'>alert('Script Created and Uploaded Succesfully!')</script>";
        } else {
            $errors[] = "An error occurred with the upload process. Try again or contact helpdesk@it.com for help.";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "\n";
            echo "<a href=\"javascript:history.go(-1)\">Try Again</a>";
        }
    }

}
?>
