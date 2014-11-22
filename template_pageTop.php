<?php 
include_once("php_includes/check_login_status.php");
?>

<?php
//Detects device type and redirects to appropriate site, either mobile or desktop site
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

if ($iphone || $android || $palmpre || $ipod || $berry == true) 
{
    echo "<script>window.location='http://m.site.com'</script>";
 }
?>

<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
$new_friends = '|<img src="images/note_dead.png" width="22" height="12" alt="Notes" title="This is for logged in members">';
$envelope = '|<img src="images/note_dead.png" width="22" height="12" alt="Notes" title="This is for logged in members">';
$pm_n = '|<img src="images/note_dead.png" width="22" height="12" alt="Fm" title="This is for logged in members only">';





$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="No notifications">|<img src="images/note_still.png" width="22" height="12" alt="Notes"></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have new notifications">|<img src="images/note_flash.gif" width="22" height="12" alt="Notes"></a>';
	}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php">Log Out</a>';
	
	
	//request count patch
$sql = "SELECT COUNT(id) from friends WHERE user2='$log_username' AND accepted='0'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$requests_count = $row[0];
if ($requests_count != 0 ){
	$new_friends = '<a href="friends.php" title="Friend Requests">|<img src="images/note_flash.gif" width="18" height="12" alt="friend">('.$requests_count.')</a>';}
	else {
		$new_friends = '<a href="friends.php" title="No Friend Requests">|<img src="images/note_still.png" width="22" height="12" alt="friend"></a>';
		}
	
	
	///////////////////////////////////////////////////
	/////////////// message header logic /////////////
$sql = "SELECT id FROM pm WHERE 
(receiver='$log_username' AND parent='x' AND rdelete='0' AND rread='0')
or 
(sender='$log_username' AND sdelete='0' AND parent='x' AND hasreplies='1' AND sread='0')
 LIMIT 10";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if ($numrows > 0 && $numrows < 9) {
	$pm_n = '<a href="pm_inbox.php?u='.$log_username.'" title="You have a message"><img src="images/note_flash.gif" width="22" height="12" alt="Fm">('.$numrows.')</a>';
}
else if ($numrows >= 10) {
	$pm_n = '<a href="pm_inbox.php?u='.$log_username.'" title="You have a message"><img src="images/note_flash.gif" width="22" height="12" alt="Fm">('.$numrows.'+)</a>';
}
else {
	$pm_n = '<a href="pm_inbox.php?u='.$log_username.'" title="No message"><img src="images/note_still.png" width="22" height="12" alt="Fm"></a>';
}
}
////////////////////////////////////////////////////////
?>
<title>Inbox</title>
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="js/fsearch.js"></script>


<div id="overlaybg">


<div id="pageTop">
  <div id="pageTopWrap">
    <div id="pageTopLogo">
      <a href="home.php">
        <img src="images/logo.png" alt="logo" title="dudu">
      </a>
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div><?php echo $pm_n; ?><?php echo $envelope; ?> <?php echo $new_friends ?> &nbsp; &nbsp; <?php echo $loginLink; ?>
        </div>
        <div id="duduSearch">
        
        </div>
      </div>
      <div id="menu2">
        <div>
          <div><a href= "home.php">Home</a>
          <a href= "search.php">| search</a>
         
          <!-- popup groups-->
          <script type="text/javascript">
<!--
function popup(url) 
{
 var width  =  900;
 var height = 500;
 var left   = (screen.width  - width)/2;
 var top    = (screen.height - height)/2;
 var params = 'width='+width+', height='+height;
 params += ', top='+top+', left='+left;
 params += ', directories=no';
 params += ', location=no';
 params += ', menubar=no';
 params += ', resizable=no';
 params += ', scrollbars=no';
 params += ', status=no';
 params += ', toolbar=no';
 newwin=window.open(url,'windowname5', params);
 if (window.focus) {newwin.focus()}
 return false;
}
// -->
</script>

<a href="group_module.php">| Groups</a>
<a href="more_friends.php">| Find friends</a>
<a href="settings.php">| Settings</a>
<a href="music.php">| Music</a>

    </div>
          <!--<a href="#">Menu_Item_1</a>
          <a href="#">Menu_Item_2</a> -->
        </div>
      </div>
    </div>
  </div>
  </div>



