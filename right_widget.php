<title>right widget</title>
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
$country2 = "";



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
		//echo "You have no friends!";
		}
	//get friends of friends array
	foreach ($my_friends as $k => $v) {
		//you may want to edit limit at the end of the following query ..... example..... limit 50
		$sql = "SELECT user1, user2 FROM friends WHERE (user1='$v' OR user2='$v') AND accepted='1' AND user1!='$u' AND user2!='$u' LIMIT 5";}
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
			$moMoFriends = '<h2 style="color: #000;">People you may know</h2>';
			
			foreach ($their_friends as $k2 => $v2) {
				$query = mysqli_query($db_conx, "SELECT avatar, country FROM users WHERE username='$v2' LIMIT 1");
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){

	$country2 = $row["country"];
				
					$moMoFriends .= '<div id="suggestion"><a href="user.php?u='.$v2.'"><img src=" user/'.$v2.'/'.$row["avatar"].'" width="50" height="50"><br />'.$v2.'</a> | '.$country2.'</div>&nbsp;&nbsp;';
					
				}
				}
				$moMoFriends .= '</div>';
			}
			?>
<div id="right-widget">
	<div><?php echo $moMoFriends; ?></div>
</div>