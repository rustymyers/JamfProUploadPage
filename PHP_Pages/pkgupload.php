<?php
// Joshua Harvey <josh@macjeezy.com>
// November 2018
// GitHub: github.com/therealmacjeezy
// JamfNation: therealmacjeezy

include('globals.php');

$errors = []; // Store all foreseen and unforseen errors here
// File Extensions go here.
$fileExtensions = ['pkg', 'dmg', 'mpkg'];
$uploadDirectory = "/Packages/";
$currentDir = getcwd();

libxml_use_internal_errors(true);

if (isset($_POST['submit'])) {

    $siteName = $_POST['jamf_site'];
    $fileName = $_FILES['jamf_package']['name'];
    $fileSize = $_FILES['jamf_package']['size'];
    $fileTmpName  = $_FILES['jamf_package']['tmp_name'];
    $fileType = $_FILES['jamf_package']['type'];

    if (empty($siteName)) {
        $errors[] = "No Site was selected. Please select a site and try again";
    }

    $fileExtension = strtolower(end(explode('.',$fileName)));
    if (! in_array($fileExtension, $fileExtensions)) {
        $errors[] = "This file extension is not allowed. Please upload a valid file type (.pkg, .mpkg, .dmg).";
    }

    // This limits the size of the upload to 6.5 GB via bytes
    if ($fileSize > 6500000000) {
        $errors[] = "This file is more than 6.5 GB limit. Please adjust the size or contact helpdesk@it.com for help.";
    }

    if (empty($errors)) {
        $pkgName = $siteName . "-" . basename($fileName);
        $updateXML = "<package><name>$pkgName</name><filename>$pkgName</filename><category>Uploads</category><info>$siteName</info><priority>15</priority></package>";

        $uploadPath = $uploadDirectory . $siteName . "-" . basename($fileName);
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
            //echo "The file " . $siteName . "-" . basename($fileName) . " has been uploaded and is available for use in the Jamf Pro Server.\r\n";
            $get_data = callAPI('POST', 'https://' . $mdmhostname . '/JSSResource/packages/id/0', $updateXML, $mdmapiusername, $mdmapipass);
            header('Refresh:0; url=index.html');
            echo "<script type='text/javascript'>alert('Package Uploaded Succesfully!')</script>";
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
