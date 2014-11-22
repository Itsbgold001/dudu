<?php
include_once("php_includes/check_login_status.php");
$u = '';
$totalUSERS = '';

$sql = "SELECT * username FROM users";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$users_count = $query_count[0];


	$sql = "SELECT * username, avatar FROM users WHERE username='$u' LIMIT 10";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$total_username = $row["username"];
		$total_avatar = $row["avatar"];
		if($total_avatar != ""){
			$total_pic = 'user/'.$total_username.'/'.$total_avatar.'';
		} else {
			$total_pic = 'images/avatardefault.jpg';
		}
		$totalUSERS .= '<div><a href="user.php?u='.$total_username.'"><img class="friendpics" src="'.$total_pic.'" alt="'.$total_username.'" title="'.$total_username.'"><div>'.$total_username.'</div></a></div>';
	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>testing total users display</title>
</head>

<body>
<?php $totalUSERS; ?>

</body>
</html>