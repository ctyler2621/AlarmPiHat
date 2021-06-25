<?php
/*SCOPE/DESCRIPTION:
This page facilitates the users login to the system.
*/
session_start();

// Check for mobile devices
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$bberry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$webos = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");

if ($android || $bberry || $iphone || $ipod || $webos == true) {
    $_SESSION['sess_mobile'] = 'true';
} else {
    $_SESSION['sess_mobile'] = 'false';
}

if($_SESSION['auth'] == "ok"){
  $settings_url = "settings.php";
}else{
  $settings_url = "auth.php";
}


if($_SESSION['sess_mobile'] == 'false'){
    // For Desktop
    print '<div align="center">';
    print '<img src="images/this_logo.png"><br />';
    print '<div id="settings"><a style="settings"href="'.$settings_url.'" title="Settings"><img src="images/settings-icon.png" height="25px"></a></div>';
} else {
    // For mobile
    print '<div align="center" style="width:290px;">';
    print '<img src="images/this_logo.png" width=290px><br />';
    print '<div id="settings"><a style="settings"href="'.$settings_url.'" title="Settings"><img src="images/settings-icon.png" height="25px"></a></div>';
}

if ($_SESSION['sess_mobile'] == 'true') {
  print '<link rel="stylesheet" type="text/css" href="css/style.css" />';
  print '<link rel="stylesheet" type="text/css" href="css/m_style.css" />'; /* Should override the master style sheet since it comes in latest*/
} else {
  print '<link rel="stylesheet" type="text/css" href="css/style.css" />';
  print '<link href="https://fonts.googleapis.com/css?family=Rubik" rel="stylesheet">';
}
// Set the default viewport for all pages
print '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
?>
<hr />
