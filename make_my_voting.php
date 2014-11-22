<?php include_once("php_includes/db_conx.php");?>
<?php 
$tbl_voting_count = "CREATE TABLE IF NOT EXISTS `voting_count` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `unique_content_id` VARCHAR(100) NOT NULL,
  `vote_up` INT(11) NOT NULL,
  `vote_down` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
$query = mysqli_query($db_conx, $tbl_voting_count);
if ($query === TRUE) {
	echo "<h3>user table created OK :) </h3>"; 
} else {
	echo "<h3>user table NOT created :( </h3>"; 
}

?>