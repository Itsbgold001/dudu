<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<script src="js/voting.js"></script>
<?php
//Need to dump all sorts of activity in the homepage based on relevance from the users past activities and likes blah blah blah

include_once("php_includes/check_login_status.php");	
//make sure the user is logged in and sanitize the session
if(isset($_SESSION['username'])){
	$u = $_SESSION['username'];
	}
	else {
		header("location: login.php");
		exit();
		}
		//get array of friends
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
		$query = mysqli_query($db_conx, $sql);
		$query_count = mysqli_fetch_row($query);
		$friends_count = $query_count[0];
		//i set the $friends_count < to zero but was initially 1 
		if($friends_count < 0) {
			echo "No Posts";
			exit();
			}
			else {
				$all_friends = array();
				$sql = "SELECT user1, user2 FROM friends WHERE (user2='$u' OR user1='$u') AND accepted='1'";
				$query = mysqli_query($db_conx, $sql);
				while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
					if($row["user1"] != $u) {array_push($all_friends, $row["user1"]);}
					if($row["user2"] != $u) {array_push($all_friends, $row["user2"]);}
					
					}
				}
				//get posts feed
				//based loosely on code in template_status.php
				$statuslist = "";
				$friendsCSV = join("','", $all_friends);
				//all 1 line
				$sql = "SELECT s.*, u.avatar FROM status AS s LEFT JOIN users AS u ON u.username = s.author WHERE s.author IN ('$friendsCSV') AND (s.type='a' OR s.type='c') ORDER BY s.postdate DESC LIMIT 50";
				$query = mysqli_query($db_conx,$sql);
				$statusnumrows = mysqli_num_rows($query);
				if($statusnumrows > 0 ) {
					while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
						$statusid = $row["id"];
	$account_name = $row["account_name"];
	$author = $row["author"];
	$postdate = $row["postdate"];
	//
	$avatar = $row["avatar"];
	$user_image = '<img src="user/'.$author.'/'.$avatar.'" width="60" height="60" border="0" />';
	//
	$data = $row["data"];
	$data = nl2br($data);
	$data = str_replace("&amp;","&",$data);
	$data = stripslashes($data);
	$statusDeleteButton = '';

	
///////////////////////////////////////////
//TIME AGO TIMESTAMP

include_once("php_parsers/classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = $postdate;
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time	
	
////////////////////////////////////////	
	if($author == $log_username || $account_name == $log_username ){
		$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="DELETE THIS STATUS AND ITS COMMENTS">delete status</a></span> &nbsp; &nbsp;';
	}
	// GATHER UP ANY STATUS REPLIES
	$status_replies = "";
	$sql12 = "SELECT s.*, u.avatar
	FROM status AS s
	LEFT JOIN users AS u ON u.username = s.author
	WHERE s.osid = '$statusid'
	AND s.type='b'
	ORDER BY postdate ASC";
	
	$query_replies = mysqli_query($db_conx, $sql12);
	$replynumrows = mysqli_num_rows($query_replies);
    if($replynumrows > 0){
        while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
			$statusreplyid = $row2["id"];
			$replyauthor = $row2["author"];
			$replydata = $row2["data"];
			//
			$avatar2 = $row2["avatar"];
			$user_image2 = '<img src="user/'.$replyauthor.'/'.$avatar2.'" width="40" height="40" border="0" />';
			//
			$replydata = nl2br($replydata);
			$replypostdate = $row2["postdate"];
			$replydata = str_replace("&amp;","&",$replydata);
			$replydata = stripslashes($replydata);
			$replyDeleteButton = '';
			
 

include_once("php_parsers/classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = $replypostdate;
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when2 = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time

			
			if($replyauthor == $log_username || $account_name == $log_username ){
				$replyDeleteButton = '<span id="srdb_'.$statusreplyid.'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\');" title="DELETE THIS COMMENT">remove</a></span>';
			}
			$status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><div><b><div id="replyAvtr">'.$user_image2.'</div><a href="user.php?u='.$replyauthor.'">'.$replyauthor.'</a> '.$when2.':</b> '.$replyDeleteButton.'<br />'.$replydata.'</div></div>';
        }
    }
	$statuslist .= '<div id="statusuiBox"><div id="status_'.$statusid.'" class="status_boxes"><div><b><div id="statusAvtr">'.$user_image.'</div><a href="user.php?u='.$author.'">'.$author.'</a> '.$when.':</b> '.$statusDeleteButton.' <br /><div id="postText"><div>'.$data.'</div></div><br /><div class="voting_wrapper" id="vote_'.$statusid.'">
            <div class="voting_btn">
                <div class="up_button">&nbsp;</div><span class="up_votes">0</span>
            </div>
            <div class="voting_btn">
                <div class="down_button">&nbsp;</div><span class="down_votes">0</span>
            </div>
        </div> <div class="rateWrapper">###</div><br /></div>'.$status_replies.'</div></div>';

	    $statuslist .= '<div id="statusuiComment"><textarea id="replytext_'.$statusid.'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$u.'\',\'replytext_'.$statusid.'\',this)">Reply</button></div>';	
	}
	
}

					
				
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Home</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/mainCSS.css">

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(data == ""){
		alert("Type something first weenis");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "post_ok"){
				var sid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				var currentHTML = _("statusarea").innerHTML;
				window.location = "home.php";
				_("statusarea").innerHTML = '<div id="status_'+sid+'" class="status_boxes"><div><b>Posted by you just now:</b> <span id="sdb_'+sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''+sid+'\',\'status_'+sid+'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span><br />'+data+'</div></div><textarea id="replytext_'+sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'+sid+'" onclick="replyToStatus('+sid+',\'<?php echo $u; ?>\',\'replytext_'+sid+'\',this)">Reply</button>'+currentHTML;
				_("statusBtn").disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action="+action+"&type="+type+"&user="+user+"&data="+data);
}
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("Type something first weenis");
		return false;
	}
	_("replyBtn_"+sid).disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				window.location = "home.php";
				_("status_"+sid).innerHTML += '<div id="reply_'+rid+'" class="reply_boxes"><div><b>Reply by you just now:</b><span id="srdb_'+rid+'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''+rid+'\',\'reply_'+rid+'\');" title="DELETE THIS COMMENT">remove</a></span><br />'+data+'</div></div>';
				_("replyBtn_"+sid).disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=status_reply&sid="+sid+"&user="+user+"&data="+data);
}
function deleteStatus(statusid,statusbox){
	var conf = confirm("Are you sure you want to delete this status and its replies?");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(statusbox).style.display = 'none';
				_("replytext_"+statusid).style.display = 'none';
				_("replyBtn_"+statusid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_status&statusid="+statusid);
}
function deleteReply(replyid,replybox){
	var conf = confirm("Are you sure you want to delete this reply?");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(replybox).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_reply&replyid="+replyid);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}
</script>

<script src="js/jquery-1.8.1.min.js"></script>

	<script src="js/jquery.autogrowtextarea.min.js"></script>
	<script>
		$(document).ready(function() {
			$("textarea").autoGrow();
		});
	</script>
    
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
<?php include_once("duduInteractive.php"); ?>
<div id="statusui">
<h3>Your friends posts</h3>
<div id="statusarea">
<?php echo $statuslist; ?>
</div>
</div>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>