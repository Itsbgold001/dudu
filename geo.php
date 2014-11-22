<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find location</title>
<script>
var latlon = "notSet";
function getLocation(){
	navigator.geolocation.getCurrentPosition(getPos,getError);
	}
	
	function getPos(position){
		latlon=position.coords.latitiude+","+position.coords.longitude;
		alert(latlon);
	}
	
	function getError(error){
		var locError = error.code;
		var latlon = "notSet";
		alert (latlon);
	}
</script>
</head>

<body>
<button onClick="getLocation()">Check location</button>
</body>
</html>