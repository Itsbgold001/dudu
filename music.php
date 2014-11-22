<?php
include_once("php_includes/check_login_status.php");
//make sure the user is logged in and sanitize the session
if(isset($_SESSION['username'])){
	$u = $_SESSION['username'];
	}
	else {
		header("location: login.php");
		exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Music</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/mainCSS.css">
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/component.css" />
<script src="js/modernizr.custom.js"></script>

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">

<?php include('upgalery/upgalery.php'); ?>
<div id="music_header">
<?php include('upgalery/form_upload.php');?>
<?php // creates the menu
echo '<div id="gmenu">
'.'<h4>'.$lsite['gaudio'].'</h4>'.
$gObj->getMenu('gaudio').
'</div>'; ?>
</div>

<?php  // create the block in which the galery is displayed
echo '<div id="galery">'; 
// if URL to access a category, adds the galery list of that category
if(isset($_GET['gl']) && isset($_GET['gc'])) echo $gObj->getGallery();
else echo '<h3>No Media</h3>.';

echo '<br class="clr"/></div>';
?>
</div>


<?php include_once("template_pageBottom.php"); ?>


</body>
</html>