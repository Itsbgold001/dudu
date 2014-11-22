
<?php

include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = md5($_POST['p']);
		
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// FORM DATA ERROR HANDLING
	if($e == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, username, password FROM users WHERE email='$e' AND activated='1' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
        $db_pass_str = $row[2];
		if($p != $db_pass_str){
			echo "login_failed";
            exit();
		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;
		    exit();
		}
	}
	exit();
}
$sql = "SELECT COUNT(id) FROM users";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$users_count = $query_count[0];
?>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("email").value;
	var p = _("password").value;
	if(e == "" || p == ""){
		_("status").innerHTML = "Fill out all the details";
	} else {
		_("loginbtn").style.display = "none";
		_("status").innerHTML = '<img src="images/loading.gif">';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = "Login unsuccessful, please try again.";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("e="+e+"&p="+p);
	}
}
</script>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Welcome to Dudu</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="site description will go here for SEO purposes" />
        <meta name="keywords" content="" />
        <meta name="author" content="Malcolm maima" />
        <link rel="shortcut icon" href="favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css">
        <link rel="stylesheet" type="text/css" href="css/style3.css" />
		<script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    </head>
    <body id="page">
        <ul class="cb-slideshow">
            <li><span>Image 01</span><div><h3>re·lax·a·tion</h3></div></li>
            <li><span>Image 02</span><div><h3>qui·e·tude</h3></div></li>
            <li><span>Image 03</span><div><h3>bal·ance</h3></div></li>
            <li><span>Image 04</span><div><h3>e·qua·nim·i·ty</h3></div></li>
            <li><span>Image 05</span><div><h3>com·po·sure</h3></div></li>
            <li><span>Image 06</span><div><h3>se·ren·i·ty</h3></div></li>
            <li><span>Image 07</span><div><h3></h3></div></li>
        </ul>
        <div class="container">
            <!-- dudu top bar -->
            
            <div class="codrops-top">
                <a href="index.php">
                    <strong>&laquo;Home</strong></a><p></p>
                <span class="right">
                    <a href="about.php" target="_blank">About</a>
                    <a href="blog.php" target="_blank">blog</a>
                    <a href="entertainment.php">
                        <strong>Entertainment</strong>
                    </a>
                </span>
                <div class="clr"></div>
            </div><!--/ dudu top bar -->
            <header>
            
 <div id="welcome">
  

  
                <h1><a href="index.php"><img src="images/logo.png" width="100" height="82" alt="dudu"></a></h1>
      <h1>Welcome <span>To Dudu</span></h1>
                <h2>Where the fun begins :)</h2>
                <div id="duduinfo">
                <h2><p>Total users signed up:</h2> <h1><?php echo $users_count; ?></h1></p></div>
				<p class="codrops-demos">
					<a  href="signup.php">Sign up</a>
					
				</p>
                <!-- Background Music-->
 <audio src="music/peponi.mp3" controls>	
	<embed 
    src="music/peponi.mp3"
	width="300"
	height="90"
	loop="true"
	autostart="true" />
</audio>
  <!-- Background music-->
              </div>
                <style type="text/css">
				div#welcome {
					float: left;
				}
				</style>
                 <!-- LOGIN FORM -->
  
  <form id="loginform" onsubmit="return false;">
    <div><h2>Email Address:</h2></div>
    <input type="text" id="email" onfocus="emptyElement('status')" maxlength="88">
    <div><h2>Password:</h2></div>
    <input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Log In</button> 
    <p id="status"></p>
    <p><a href="forgot_pass.php">Forgot Your Password?</a>
    </p>
    <p><a href="signup.php">Sign up</a></p>
  </form>
            </header>
        </div>
        <?php include_once("track.php");?>
    </body>
</html>
