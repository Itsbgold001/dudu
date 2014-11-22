<?php
include_once("php_includes/check_login_status.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
	header("location: login.php");
    exit();
}
$max = 20;
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC LIMIT $max" ;
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);


if($numrows < 1){
	$notification_list = "You do not have any notifications";
} else {
	
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		//$date_time = strftime("%b %d, %Y", strtotime($date_time));		
		///////////////////////////////////////////
//TIME AGO TIMESTAMP

include_once("php_parsers/classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = $date_time;
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time	
	
////////////////////////////////////////	
	$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$initiator' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$noteavatar = $thumbrow[0];
		$notepic = '<img src="user/'.$initiator.'/'.$noteavatar.'" alt="'.$initiator.'" class="user_pic">';


		$notification_list .= "<div>$notepic<p><a href='user.php?u=$initiator'> | $note</a> | $when<br /></p></div></a>";
		
	}

 if($notification_list > 10){
		array_splice($notification_list, $max);}
}
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/mainCSS.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">

</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <!-- START Page Content -->
  <?php include_once("duduInteractive.php"); ?>
  <div id="notesBox"><h2>Notifications</h2><?php echo $notification_list; ?></div>
  <div style="clear:left;"></div>
  <!-- END Page Content -->
</div>
<div align="right">

  <?php include_once("template_pageBottom.php"); ?>
</div>
</body>
</html>