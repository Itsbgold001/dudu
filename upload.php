<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Images and Audio Gallery</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<style type="text/css"><!--
body {
 margin:1px 4%;
 text-align:center;
}
#gmenu {
 position:absolute;
 margin:0 auto auto 0;
 width:200px;
 border:1px solid blue;
 padding:2px;
}
#galery {
 position:relative;
 margin:0 auto 20px 210px;
 border:1px solid green;
 padding:2px;
 text-align:left;
}
#upblock {
 margin:2px auto;
 background:#e7e8fe;
 width:500px;
 padding:2px 0;
}
#galery .gimgs {
 float:left;
 margin:3px 4px;
 border:1px solid orange;
 padding:2px;
}
#galery .clr { clear:both; }
--></style>
</head>
<body>
<h1>Images and Audio</h1>

<?php
include('upgalery/upgalery.php');         // include the script

// creates the menu
echo '<div id="gmenu">
<h4>'.$lsite['gimgs'].'</h4>'.
$gObj->getMenu('gimgs').

'<h4>'.$lsite['gaudio'].'</h4>'.
$gObj->getMenu('gaudio').
'</div>';

// create the block in which the galery is displayed
echo '<div id="galery">';

// if URL to access a category, adds the galery list of that category
if(isset($_GET['gl']) && isset($_GET['gc'])) echo $gObj->getGallery();
else echo '<h3>Here will be displayed the galery list of accessed category</h3>The page is just for test, with a minimum design for HTML elements.';

echo '<br class="clr"/></div>';

// include the form for upload
include('upgalery/form_upload.php');
?>

</body>
</html>