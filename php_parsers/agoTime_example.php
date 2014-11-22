<?php
include_once("classes/dudu_php_library.php"); // Include the class library
$timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
// Query your database here and get timestamp
$ts = "2013-12-23 23:30:18";
$convertedTime = ($timeAgoObject -> convert_datetime($ts)); // Convert Date Time
$when = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time
?>

<h2><?php echo $when; ?></h2>