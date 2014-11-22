<?php 
include_once("php_includes/check_login_status.php");
?>
<?php 
$thisPage = basename($_SERVER['PHP_SELF']);
$thisGroup = "";
$agList = "";
$mgList = "";
$_SESSION['group'] = "notSet";
if ($thisPage == "group.php"){
	if(isset($_GET["g"])){
		$thisGroup = preg_replace('#[^a-z0-9_]#i', '', $_GET['g']);
		$_SESSION['group'] = $thisGroup;
	}
}
if (isset($_SESSION['username'])) {
// All groups list	
	$query = mysqli_query($db_conx, "SELECT name,logo FROM groups");
	$g_check = mysqli_num_rows($query);
	if ($g_check > 0){
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$agList .= '<a href="group.php?g='.$row["name"].'" ><img src="groups/'.$row["name"].'/'.$row["logo"].'" alt="'.$row["name"].'" title="'.$row["name"].'" width="50" height="50" border="0" /></a>';
		}
	}
// My groups list	
	$sql = "SELECT gm.gname, gp.logo
			FROM gmembers AS gm
			LEFT JOIN groups AS gp ON gp.name = gm.gname
			WHERE gm.mname = '$log_username'";
	$query = mysqli_query($db_conx, $sql);
	$g_check = mysqli_num_rows($query);
	if ($g_check > 0){
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$mgList .= '<a href="group.php?g='.$row['gname'].'"><img src="groups/'.$row['gname'].'/'.$row['logo'].'" alt="'.$row['gname'].'" title="'.$row['gname'].'" width="50" height="50" border="0" /></a>';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style/mainCSS.css">
<title>Groups</title>
</head>

<body>
</body>
</html>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
var isShowing = "no";
function showGroups() {
	if(isShowing == "no"){
		_('pageMiddle').innerHTML = '<div id="groupWrapper2"><div id="groupList"><div id="groupForm"><h4>Create New Group</h4><hr /><p>Group Name:<br /><input type="text" id="gname" onBlur="checkGname()" ><span id="gnamestatus"></span></p><p>Type of group:<br /><select name="invite" id="invite"><option value="null" selected>&nbsp;</option><option value="1">Private.</option><option value="2">Public.</option></select></p><button id="newGroupBtn" onClick="createGroup()">Create Group</button><span id="status"></span></div><div id="myGroups"><h4>My Groups</h4><hr /><?php echo $mgList; ?></div><div id="allGroups"><h4>All Groups</h4><hr /><?php echo $agList; ?></div></div></div><div class="clear"></div>';
		isShowing = "yes";
	} else {
		_('pageMiddle').innerHTML = '';
		isShowing = "no";
	}
}
function checkGname(){
	var u = _("gname").value;
	var rx = new RegExp;
	rx = /[^a-z 0-9_]/gi;
	u = u.replace(rx, "");
	var rxx = new RegExp;
	rxx = /[ ]/g;
	u = u.replace(rxx, "_");
	
	if(u != ""){
		_("gnamestatus").innerHTML = '<img src="images/loading.gif">';
		var ajax = ajaxObj("POST", "php_parsers/group_parser.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("gnamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("gnamecheck="+u);
	}
}
function createGroup(){
	var name = _("gname").value;
	var inv = _("invite").value;
	if(name == "" || inv == "null"){
		alert("Fill all fields");
		return false;
	} else {
		status.innerHTML = '<img src="images/loading.gif">';
		var ajax = ajaxObj("POST", "php_parsers/group_parser.php");
		ajax.onreadystatechange = function() {
			if(ajaxReturn(ajax) == true) {
				var datArray = ajax.responseText.split("|");
				if(datArray[0] == "group_created"){
				var sid = datArray[1];
					window.location = "group.php?g="+sid;
					
				} else {
					alert(ajax.responseText);
				}
			}
		}
		ajax.send("action=new_group&name="+name+"&inv="+inv);
	}	
}
</script>
<?php include_once('template_pageTop.php');?>

<div id="pageMiddle">
<?php if(isset($_SESSION['username'])) { ?>
          <a href="#"><img src="images/loading.gif" alt="groups" border="0" title="groups" onLoad="showGroups()" /></a>
          <?php } ?>
</div>


<?php  include_once('template_pageBottom.php');?>
