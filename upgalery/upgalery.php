<?php

if(!headers_sent()) header('Content-type: text/html; charset=utf-8');        // header for utf-8

        /* Settings for Admin */

// HERE add you data for connecting to MySQL database (MySQL server, user, password, database name)
$mysql['host'] = 'localhost';
$mysql['user'] = 'root';
$mysql['pass'] = '';
$mysql['bdname'] = 'dudu';

// HERE you cand edit the permissions for uploded images and audio file
$upfrule = array(
  'imgext' => array('gif', 'jpg', 'jpe', 'png'),        // allowed extensions for images
  'width' => 2400,               // maximum allowed width, in pixels
  'height' => 2400,              // maximum allowed height, in pixels
  'wthmb'=> 250,                // width for thumbail image
  'hthmb'=> 250,                // height for thumbail image
  'imgsize' => 10000,             // maximum allowed size for the image file, in KiloBytes (10 MB)
  'audioext' => array('mp3', 'mp4', 'wma'),       // allowed extensions for audio files
  'audiosize' =>20000,               // maximum allowed size for the image file, in KiloBytes (20 MB)
);

include('texts.php');             // file with the texts for different languages
$lsite = $en_site;                // Gets the language for site

     /* From Here no need to modify */

// removes additional tags, and external whitespace from GET
if(isset($_GET)) {
  $_GET = array_map("strip_tags", $_GET);
  $_GET = array_map("trim", $_GET);
}

// include the UploadGalery class, and create instance object
include('class.UploadGalery.php');
$gObj = new UploadGalery();