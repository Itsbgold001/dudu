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
<html>
<head>
<title>Search</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="style/mainCSS.css">
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/fsearch.js"></script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>

<div id="pageMiddle" >
<?php include_once("duduInteractive.php"); ?>

<div id="search">
<div id="searchMast">
<h2>Dudu Search</h2>
<h1>Search for friends on dudu</h1>
</div>
 <div class="container">
  <input id="searchInput" placeholder="Search for people, places and things"/>
</div>
</div>

</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
<script>
$(document).ready(function()
{
  $('#searchInput').fsearch();
});
</script>
</html>