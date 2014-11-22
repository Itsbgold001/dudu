<?php
echo '<div id="upblock">
<div id="ifrm"> </div>
<form id="uploadform" action="upgalery/upgalery.php" method="post" enctype="multipart/form-data" target="uploadframe">'.
 $lsite['selgalery'].
 '<label for="rgli"><input type="radio" name="gl" value="'.$lsite['gimgs'].'" id="rgli" /><b>'.ucwords($lsite['gimgs']).'</b></label> &nbsp;
 <label for="rgla"><input type="radio" name="gl" value="'.$lsite['gaudio'].'" id="rgla" /><b>'.ucwords($lsite['gaudio']).'</b></label><br/>
 <div id="gc"></div>
 <div id="upfields">
  <div>
   <span style="cursor:pointer;font-weighht:800;color:red;" class="dlf">X</span>
   <input type="text" name="title[]" value="'.$lsite['title'].'" size="14" maxlength="100" />
   <input type="text" name="descript[]" value="'.$lsite['descript'].'" size="18" maxlength="250" />
   <input type="file" class="fup" name="fup[]" size="14" />
  </div>
 </div><br/>
 <input type="submit" value="'.$lsite['upload'].'" id="sbmt" />
</form>
<button id="newup">'.$lsite['newup'].'</button>

</div>
<script type="text/javascript"><!--
var selctg = "'.$lsite['selcateg'].'";
// options with categories to be added in <select> (according to checked radio button)
var goption = {\''.$lsite['gimgs'].'\':\''.$gObj->getOptions('gimgs').'\', \''.$lsite['gaudio'].'\':\''.$gObj->getOptions('gaudio').'\'};
// allowed extensions
var alowext = {"'.$lsite['gimgs'].'":'.json_encode($upfrule['imgext']).', "'.$lsite['gaudio'].'":'.json_encode($upfrule['audioext']).'};
// allowed data, to be displayed in form
var seealow = {"'.$lsite['gimgs'].'":"'.strtoupper(implode(', ', $upfrule['imgext'])).$lsite['maxim'].$upfrule['width'].'x'.$upfrule['height'].' px, '.$upfrule['imgsize'].'KB", "'.$lsite['gaudio'].'":"'.strtoupper(implode($upfrule['audioext'])).$lsite['maxim'].$upfrule['audiosize'].'KB"};
var eror_ext = "'.$lsite['eror_ext'].'";
--></script>
<script type="text/javascript" src="upgalery/upload.js"></script>';