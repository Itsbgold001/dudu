<?php
include_once("php_includes/db_conx.php");

$tbl_users = "CREATE TABLE IF NOT EXISTS users (
              id INT(11) NOT NULL AUTO_INCREMENT,
			  username VARCHAR(16) NOT NULL,
			  email VARCHAR(255) NOT NULL,
			  password VARCHAR(255) NOT NULL,
			  gender ENUM('m','f') NOT NULL,
			  website VARCHAR(255) NULL,
			  country VARCHAR(255) NULL,
			  userlevel ENUM('a','b','c','d') NOT NULL DEFAULT 'a',
			  avatar VARCHAR(255) NULL,
			  ip VARCHAR(255) NOT NULL,
			  signup DATETIME NOT NULL,
			  lastlogin DATETIME NOT NULL,
			  notescheck DATETIME NOT NULL,
			  activated ENUM('0','1') NOT NULL DEFAULT '0',
              PRIMARY KEY (id),
			  UNIQUE KEY username (username,email)
             )";
$query = mysqli_query($db_conx, $tbl_users);
if ($query === TRUE) {
	echo "<h3>user table created OK :) </h3>"; 
} else {
	echo "<h3>user table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_useroptions = "CREATE TABLE IF NOT EXISTS useroptions ( 
                id INT(11) NOT NULL,
                username VARCHAR(16) NOT NULL,
				background VARCHAR(255) NOT NULL,
				question VARCHAR(255) NULL,
				answer VARCHAR(255) NULL,
                PRIMARY KEY (id),
                UNIQUE KEY username (username) 
                )"; 
$query = mysqli_query($db_conx, $tbl_useroptions); 
if ($query === TRUE) {
	echo "<h3>useroptions table created OK :) </h3>"; 
} else {
	echo "<h3>useroptions table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_friends = "CREATE TABLE IF NOT EXISTS friends ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user1 VARCHAR(16) NOT NULL,
                user2 VARCHAR(16) NOT NULL,
                datemade DATETIME NOT NULL,
                accepted ENUM('0','1') NOT NULL DEFAULT '0',
                PRIMARY KEY (id)
                )"; 
$query = mysqli_query($db_conx, $tbl_friends); 
if ($query === TRUE) {
	echo "<h3>friends table created OK :) </h3>"; 
} else {
	echo "<h3>friends table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_blockedusers = "CREATE TABLE IF NOT EXISTS blockedusers ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                blocker VARCHAR(16) NOT NULL,
                blockee VARCHAR(16) NOT NULL,
                blockdate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_blockedusers); 
if ($query === TRUE) {
	echo "<h3>blockedusers table created OK :) </h3>"; 
} else {
	echo "<h3>blockedusers table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_status = "CREATE TABLE IF NOT EXISTS status ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                osid INT(11) NOT NULL,
                account_name VARCHAR(16) NOT NULL,
                author VARCHAR(16) NOT NULL,
                type ENUM('a','b','c') NOT NULL,
                data TEXT NOT NULL,
                postdate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_status); 
if ($query === TRUE) {
	echo "<h3>status table created OK :) </h3>"; 
} else {
	echo "<h3>status table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_photos = "CREATE TABLE IF NOT EXISTS photos ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user VARCHAR(16) NOT NULL,
                gallery VARCHAR(16) NOT NULL,
				filename VARCHAR(255) NOT NULL,
                description VARCHAR(255) NULL,
                uploaddate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_photos); 
if ($query === TRUE) {
	echo "<h3>photos table created OK :) </h3>"; 
} else {
	echo "<h3>photos table NOT created :( </h3>"; 
}
////////////////////////////////////
$tbl_notifications = "CREATE TABLE IF NOT EXISTS notifications ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                username VARCHAR(16) NOT NULL,
                initiator VARCHAR(16) NOT NULL,
                app VARCHAR(255) NOT NULL,
                note VARCHAR(255) NOT NULL,
                did_read ENUM('0','1') NOT NULL DEFAULT '0',
                date_time DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_notifications); 
if ($query === TRUE) {
	echo "<h3>notifications table created OK :) </h3>"; 
} else {
	echo "<h3>notifications table NOT created :( </h3>"; 
}
////////////////////////////////////////////////////
$tbl_updates = "CREATE TABLE IF NOT EXISTS `updates` (
`update_id` INT NOT NULL primary key AUTO_INCREMENT ,
`update` TEXT NULL ,
`time` INT NULL ,
`host` VARCHAR(45) NULL ,
`vote_up` INT NULL , 
`vote_down` INT NULL ,
`user_id_fk` INT NULL ,
FOREIGN KEY (user_id_fk) REFERENCES users(id)
)";
$query = mysqli_query($db_conx, $tbl_updates);
if ($query === TRUE) {
	echo "<h3>updates table created OK :) </h3>"; 
} else {
	echo "<h3>updates table NOT created :( </h3>"; 
}
/////////////////////////////////////////////////////
$tbl_comments = "CREATE TABLE IF NOT EXISTS `comments` (
`comment_id` INT NOT NULL primary key AUTO_INCREMENT ,
`comment` TEXT NULL ,
`time` INT NULL ,
`host` VARCHAR(45) NULL ,
`update_id_fk` INT NULL ,
`user_id_fk` INT NULL ,
FOREIGN KEY (update_id_fk) REFERENCES updates(update_id ),
FOREIGN KEY (user_id_fk) REFERENCES users(id)
)";
$query = mysqli_query($db_conx, $tbl_comments);
if ($query === TRUE) {
	echo "<h3>comments table created OK :) </h3>"; 
} else {
	echo "<h3>comments table NOT created :( </h3>"; 
}
/////////////////////////////////////////////////

$tbl_vote = "CREATE TABLE IF NOT EXISTS `vote` (
`vote_id` INT NOT NULL primary key AUTO_INCREMENT ,
`vote_host` VARCHAR(45) NULL ,
`update_id_fk` INT NULL ,
FOREIGN KEY (update_id_fk) REFERENCES updates(update_id)
)";
$query = mysqli_query($db_conx, $tbl_vote);
if ($query === TRUE) {
	echo "<h3>vote table created OK :) </h3>"; 
} else {
	echo "<h3>vote table NOT created :( </h3>"; 
}
///////////////////////////////////////////////////

$tbl_conversation = "CREATE TABLE  IF NOT EXISTS `conversation` (
`c_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`user_one` int(11) NOT NULL,
`user_two` int(11) NOT NULL,
`ip` varchar(30) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
FOREIGN KEY (user_one) REFERENCES users(id),
FOREIGN KEY (user_two) REFERENCES users(id)
)";
$query = mysqli_query($db_conx, $tbl_conversation);
if ($query === TRUE) {
	echo "<h3>conversation table created OK :) </h3>"; 
} else {
	echo "<h3>conversation table NOT created :( </h3>"; 
}

//////////////////////////////////////////////////////

$tbl_conversationreply = "CREATE TABLE `conversationreply` (
`cr_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`reply` text,
`user_id_fk` int(11) NOT NULL,
`ip` varchar(30) NOT NULL,
`time` int(11) NOT NULL,
`c_id_fk` int(11) NOT NULL,
FOREIGN KEY (user_id_fk) REFERENCES users(id),
FOREIGN KEY (c_id_fk) REFERENCES conversation(c_id)
)";
$query = mysqli_query($db_conx, $tbl_conversationreply);
if ($query === TRUE) {
	echo "<h3>conversationreply table created OK :) </h3>"; 
} else {
	echo "<h3>conversationreply table NOT created :( </h3>"; 
}
//////////////////////////////////////////////////
?>