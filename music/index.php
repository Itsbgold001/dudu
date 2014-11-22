<?php ob_start("ob_gzhandler"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/TR/REC-html40">
<head>
<LINK REL="stylesheet" TYPE="text/css" MEDIA="all" HREF="../style.css.php">
<script LANGUAGE="javascript" TYPE="text/javascript" SRC="../js/script.js.php"></script>
<script type="text/javascript">
<!--//
function shrink(){
 document.getElementById("MediaPlayer1").style.width=384;
 document.getElementById("MediaPlayer1").style.height=300;
}
function enLarge(){
 document.getElementById("MediaPlayer1").style.width=480;
 document.getElementById("MediaPlayer1").style.height=402;
}
 //-->
</script>
<title></title>
</head><body><a name='top'></a><H3><center>Videos</center></h3>
<p class='logo'>
<button onclick="enLarge()">large</button>
<button onclick="shrink()">small</button>
<a href='..'>Main Menu</a>
<A HREF="http://www.microsoft.com/windows/windowsmedia/player/download/"><IMG ALT="Get Windows Media Player" SRC="http://www.microsoft.com/windows/windowsmedia/images/logos/getwm/mp11_88x31_static.gif" WIDTH="88" HEIGHT="31" BORDER="0"></A><br>
<OBJECT ID="MediaPlayer1" width=384 height=300 classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,02,902" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
<PARAM NAME="fileName" VALUE="">
<PARAM NAME="showControls" VALUE="true">
<PARAM NAME="PlayCount" VALUE="0">
<PARAM NAME="animationatStart" VALUE="true">
<PARAM NAME="transparentatStart" VALUE="true">
<PARAM NAME="autoStart" VALUE="true">
<EMBED type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" SRC="" name="MediaPlayer1" width=360 height=260 AutoStart=true></EMBED></object></p>
<div style='float:left; text-align:left;'>
<?php $p = split('/', $_SERVER['SCRIPT_FILENAME']);
	$script_name = $p[count($p)-1];
	$path = str_replace($script_name, '', $_SERVER['SCRIPT_FILENAME']);
	$dir_handle = @opendir($path) or die("Unable to open $path");
	Function get_Extension($m_FileName){
 	$path_parts = pathinfo($m_FileName);
 	if ($path_parts["extension"]) {
 		$m_Extension = strtolower($path_parts["extension"]);
 		return(strtoupper($m_Extension));
 		}
 	else { return "unknown"; }
 }
 function check_image($filename){
 $temp=strtoupper(get_Extension($filename));
 if(($temp=="MP3")||($temp=="WMA")||($temp=="WMV")||($temp=="ASF"))  return (true);
 else return (false);
 }
 Function get_Files($path) {
 	if ($handle = opendir($path)) {	
 		while (false !== ($file = readdir($handle))) { 
 		if(!is_dir($file) && substr($file,O,1) != "."){				
				$m_Files[]=$file;
 			}
 		}
 closedir($handle); 
 	}
 if(sizeof($m_Files)>1)
 asort($m_Files);
 return $m_Files;
 }
 $files=get_Files($path); 
 $filter_files=array_filter($files,"check_image");
 $maxnr=sizeof($filter_files)-1;
 sort($filter_files);
for ($i=0;$i<sizeof($filter_files);$i++){
	echo "<a class='button' onclick=\"MediaPlayer1.SRC='$filter_files[$i]';MediaPlayer1.fileName='$filter_files[$i]';\">";
	echo substr($filter_files[$i], 0, strlen($filter_files[$i])-4);
	echo "</a><br>";
 }
closedir($dir_handle); ?></table>

</body></html>
<?php ob_flush(); ?>