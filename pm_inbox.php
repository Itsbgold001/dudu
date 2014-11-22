<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$mail = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: index.php");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){$isOwner = "yes";}
if($isOwner != "yes"){header("location: index.php");exit();}
// Get list of parent pm's not deleted
$sql = "SELECT * FROM pm WHERE 
(receiver='$u' AND parent='x' AND rdelete='0') 
OR 
(sender='$u' AND sdelete='0' AND parent='x' AND hasreplies='1') 
ORDER BY senttime DESC";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
// Gather data about parent pm's
if($statusnumrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$pmid = $row["id"];
		//div naming
		$pmid2 = 'pm_'.$pmid;
		$wrap = 'pm_wrap_'.$pmid;
		//button naming
		$btid2 = 'bt_'.$pmid;
		//textarea naming
		$rt = 'replytext_'.$pmid;
		//button naming
		$rb = 'replyBtn_'.$pmid;
		$receiver = $row["receiver"];
		$sender = $row["sender"];
		$subject = $row["subject"];
		$message = $row["message"];
		$time = $row["senttime"];
		$rread = $row["rread"];
		$sread = $row["sread"];
		//fetching the avatars here :)
		$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$sender' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$sender1avatar = $thumbrow[0];
		$sender1pic = '<img src="user/'.$sender.'/'.$sender1avatar.'" alt="'.$sender.'" class="user_pic">';
			
		
		///////////////////////////////////////////
//TIME AGO TIMESTAMP

include_once("php_parsers/classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = $time;
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time	
	
////////////////////////////////////////
		
		
	
		// Start to build our list of parent pm's
		$mail .= '<div id="'.$wrap.'" class="pm_wrap">';
		$mail .= '<div class="pm_header">'.$sender1pic.''.$subject.'<br />';
		// Add button for mark as read
		$mail .= '<button onclick="markRead(\''.$pmid.'\',\''.$sender.'\')">Mark As Read</button>';
		// Add Delete button
		$mail .= '<button id="'.$btid2.'" onclick="deletePm(\''.$pmid.'\',\''.$wrap.'\',\''.$sender.'\')">Delete</button></div>';
		$mail .= '<div id="'.$pmid2.'">';//start expanding area
		$mail .= '<hr><div class="pm_post">'.$sender1pic.''.$sender.' >> '.$receiver.' - '.$when.'<br />'.$message.'</div><hr>';
		
		// Gather up any replies to the parent pm's
		$pm_replies = "";
		$query_replies = mysqli_query($db_conx, "SELECT sender, message, senttime FROM pm WHERE parent='$pmid' ORDER BY senttime ASC");
		$replynumrows = mysqli_num_rows($query_replies);
    	if($replynumrows > 0){
			while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
				$rsender = $row2["sender"];
				$reply = $row2["message"];
				$time2 = $row2["senttime"];
				
				
				// reply from avatar
		$thumbquery2 = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$rsender' LIMIT 1");
		$thumbrow2 = mysqli_fetch_row($thumbquery2);
		$receiver1avatar = $thumbrow2[0];
		$receiver1pic = '<img src="user/'.$rsender.'/'.$receiver1avatar.'" alt="'.$rsender.'" class="user_pic">';	
				
				///////////////////////////////////////////
//TIME AGO TIMESTAMP

include_once("php_parsers/classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = $time2;
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time	
	
////////////////////////////////////////
				
				$mail .= '<br /><div class ="pm_post">Reply From: '.$receiver1pic.''.$rsender.' on '.$when.'....<br />'.$reply.'<br /></div><hr>';
			}
		}
		// Each parent and child is now listed
		$mail .= '</div>';
		// Add reply textbox
		$mail .= '<textarea id="'.$rt.'" placeholder="Reply..." style="width: 450px; margin-left: 100px; padding: 5px;"></textarea><br />';
		// Add reply button
		$mail .= '<button id="'.$rb.'" onclick="replyToPm('.$pmid.',\''.$u.'\',\''.$rt.'\',\''.$rb.'\',\''.$sender.'\')">Reply</button>';
		$mail .= '</div>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script src="js/expand_retract.js"></script>
<script language="javascript" type="text/javascript">
function replyToPm(pmid,user,ta,btn,osender){	
	var data = _(ta).value;
	if(data == ""){
		alert("Type something first weenis");
		return false;
	}
	_(btn).disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("pm_"+pmid).innerHTML += '<p><b>Reply by you just now:</b><br />'+data+'</p>';
				expand("pm_"+pmid);
				_(btn).disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=pm_reply&pmid="+pmid+"&user="+user+"&data="+data+"&osender="+osender);
}
function deletePm(pmid,wrapperid,originator){
	var conf = confirm(originator+"Are you sure you want to delete this message and its replies?");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(wrapperid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_pm&pmid="+pmid+"&originator="+originator);
}
function markRead(pmid,originator){
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "read_ok"){
				alert("Message has been marked as read");
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=mark_as_read&pmid="+pmid+"&originator="+originator);
}
</script>
<link rel="stylesheet" href="style/mainCSS.css">

</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
<script src="js/jquery-1.8.1.min.js"></script>

	<script src="js/jquery.autogrowtextarea.min.js"></script>
	<script>
		$(document).ready(function() {
			$("textarea").autoGrow();
		});
	</script>
    
<?php echo $mail; ?>
</div>
<?php include_once('template_pageBottom.php');?>
</body>
</html>