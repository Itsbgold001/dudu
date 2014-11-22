<?php 
include_once("php_includes/check_login_status.php");
// make sure the user is logged in
if(isset($_SESSION['username'])) {
	$u = $_SESSION['username'];
}
else {
	echo "You need to be logged in!";
	exit();
}
//initialize some things
$moMoFriends = "";
$my_friends = array();
$their_friends = array();

// Get friend array
$sql = "SELECT user1, user2 FROM friends WHERE (user1 ='$u' OR user2 ='$u') AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if ($numrows > 0) {
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($my_friends, $row["user2"]);
		array_push($my_friends, $row["user1"]);
	}
	//remove your id from array
	$my_friends = array_diff($my_friends, array($u));
	//reset the key values
	$my_friends = array_values($my_friends);
	mysqli_free_result($query);
	}
	else {
		echo "You have no friends!";}
	//get friends of friends array
	foreach ($my_friends as $k => $v) {
		//you may want to edit limit at the end of the following query ..... example..... limit 50
		$sql = "SELECT user1, user2 FROM friends WHERE (user1='$v' OR user2='$v') AND accepted='1' AND user1!='$u' AND user2!='$u' LIMIT 100";}
		$query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($query);
		if ($numrows > 0) {
			while($row
			 = mysqli_fetch_array($query, MYSQLI_ASSOC)){
				 array_push($their_friends, $row["user2"]);
				 array_push($their_friends, $row["user1"]);
				 }
		}
		//remove any duplicates
		$their_friends = array_unique($their_friends);
		//remove common friends
		$their_friends = array_diff($their_friends, $my_friends);
		//reset array values
		$their_friends = array_values($their_friends);
		mysqli_free_result($query);
		
		//build output from results 
		if (array_key_exists('0', $their_friends)){
			$moMoFriends = '<div id="moMoFriends">';
			$moMoFriends = '<h2>People you may know</h2>';
			
			foreach ($their_friends as $k2 => $v2) {
				$query = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$v2' LIMIT 1");
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
					$moMoFriends .= '<div id="moreFriends"><a href="user.php?u='.$v2.'"><img src=" user/'.$v2.'/'.$row["avatar"].'" width="60" height="60"><br />'.$v2.'</a></div>&nbsp;&nbsp;';
				}
				}
				$moMoFriends .= '</div>';
			}
			?>
            

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Find more friends</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/mainCSS.css">
</head>

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<body>
<?php include_once("template_pageTop.php");?>
<div id="pageMiddle">
<div id="moreFriendsSuggestion">

<div id="dudu_users">
<h1>Total dudu users currently signed up: </h1>
<div><?php $totalUSERS; ?></div>
</div>

<div><?php echo $moMoFriends; ?></div>
</div>
</div>
<?php include_once("template_pageBottom.php");?>
</body>
</html>

	