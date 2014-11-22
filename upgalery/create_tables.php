<?php
include('upgalery.php');
$conn = $gObj->setConn($mysql);         // get the connection to MySQL

// Table that stores data for images
$sqlc[$gObj->gimgs] = "CREATE TABLE `$gObj->gimgs` (`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `file` VARCHAR(150), `title` VARCHAR(100), `descript` VARCHAR(250), `category` VARCHAR(100) NOT NULL DEFAULT '', `dtreg` INT(11) NOT NULL) CHARACTER SET utf8 COLLATE utf8_general_ci";

// Table that stores data for images
$sqlc[$gObj->gaudio] = "CREATE TABLE `$gObj->gaudio` (`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `file` VARCHAR(150), `title` VARCHAR(100), `descript` VARCHAR(250), `category` VARCHAR(100) NOT NULL DEFAULT '', `dtreg` INT(11) NOT NULL) CHARACTER SET utf8 COLLATE utf8_general_ci";

// traverse the $sqlc array, and calls the method to create the tables
foreach($sqlc AS $tab=>$sql) {
  if($conn->exec($sql) !== false) echo sprintf($lsite['create_tables'], $tab);
}