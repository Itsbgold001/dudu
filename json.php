<?php 
/**
* fSearch 2.0 - jQuery plugin for facebook type search suggestions
*
* http://www.codenx.com/
*
* Copyright (c) 2013 CodenX 
*/
if($_GET)
{
$q=$_GET['searchword'];
$items = array();
include('includes/db.php'); // Includes database connection settings.
$sql_res=mysql_query("select id,username,email,avatar,country FROM users where username like '%$q%' or email like '%$q%' or country like '%$q%' order by id LIMIT 5");
//we limit the number of search results as seen above
while($row=mysql_fetch_array($sql_res))
{
$uid = $row['id'];
$username=$row['username'];
$email=$row['email'];
$avatar=$row['avatar'];
$media=''.$username.'/'.$avatar.'';
$country=$row['country'];
//original code for below variables $b_username= <b>'.$q.'</b>; Same for $b_username, $b_email and $country below
$b_username= $q;
$b_email= $q;
$b_country= $q;
$final_username = str_ireplace($q, $b_username, $username);
$final_email = str_ireplace($q, $b_email, $email);
$final_country = str_ireplace($q, $b_country, $country);
$items[] = array('id' => $uid, 'username' => $final_username, 'email' => $final_email, 'country' => $final_country, 'avatar' => $media);
}
header('Content-Type:text/json');
echo json_encode($items);
}
else{
	echo json_encode('No search string found');
}
?>