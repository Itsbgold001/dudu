// Copyright Dudu 2014
var gal = '';            // store the galery type (image, or audio)

// create the object with functions for the script
var upGalery = new Object();
 upGalery.theform = document.getElementById('uploadform');           // get object of the form
 upGalery.formcode = upGalery.theform.innerHTML;           // code of the form
 upGalery.upfields = document.getElementById('upfields').innerHTML;           // fields for upload
 // function to add the <select> list with categories, and allowed data, and make it visible
 upGalery.setSelect = function(gl) {
   gal = gl;
   document.getElementById('gc').style.display = 'block';
   document.getElementById('gc').innerHTML = selctg+': <select id="gcsel" name="gc" onchange="upGalery.selCateg();">'+goption[gal]+'</select><br/><sup id="seealow" style="visibility:hidden;color:#0102da;">&gt; '+seealow[gal]+' &lt;</sup>';
   document.getElementById('upfields').innerHTML = this.upfields;     // restore upload fields in form
   document.getElementById('upfields').style.display = 'none';        // hides  upload boxes
   document.getElementById('newup').style.display = 'none';           // hides button that adds new boxes
   document.getElementById('sbmt').setAttribute('disabled', 'disabled');     // disable Submit
 }
 // Function that adds a new box for upload in the form
  upGalery.newUpload = function(form_id) {
   // Gets the element before which the new box will be added
   var upnodes = document.getElementById('upfields').childNodes;
   var lastupnode = upnodes[upnodes.length - 1]

   // create a DIV with the new upload fields
   var newup = document.createElement('div');
   newup.innerHTML = upGalery.upfields
   document.getElementById('upfields').insertBefore(newup, lastupnode);

   upGalery.regDelandChg();        // onclick for delete [X], and onchange for upload fields
 }
 // Function that sends form data, by passing them to an iframe
 upGalery.uploading = function() {
   // Adds the code for the iframe
   document.getElementById('ifrm').innerHTML = '<iframe id="uploadframe" name="uploadframe" src="upgalery/upgalery.php" frameborder="0" style="width:99%; background:#f7f8b8;"></iframe>';

    // show 'Loading...' in <iframe> (till that page is loading), and submit form
    if(document.getElementById('uploadframe').contentWindow) {
      document.getElementById('uploadframe').contentWindow.document.write('<h1 style="text-align:center;"><img src="images/loading.gif"></h1>');
    }

   upGalery.theform.submit();      // submit the form
   upGalery.regEvents();

   return false;
 }
 // for <select> list, reset the fields for upload, make visible fields for upload, enable submit
   upGalery.selCateg = function() {
     document.getElementById('upfields').innerHTML = upGalery.upfields;
     document.getElementById('seealow').style.visibility = 'visible';
     document.getElementById('upfields').style.display = 'block';
     document.getElementById('newup').style.display = 'block';
     document.getElementById('sbmt').removeAttribute('disabled');

     // disable Submit if select <option> without category
     if(document.getElementById('gcsel').value == '---' || document.getElementById('gcsel').value == '') {
       document.getElementById('sbmt').setAttribute('disabled', 'disabled');
     }

     upGalery.regDelandChg();        // onclick for delete [X], and onchange for upload fields
   }
 // register onclick for delete [X], and onchange to fields with file for upload (to check extension)
 upGalery.regDelandChg = function() {
  // gets all <span> in the "upfields", traverse the array, if class is "dlf", register onclick
  var sptgs = document.getElementById('upfields').getElementsByTagName('span');
  var nr_sptgs = sptgs.length;

  for(var i=0; i<nr_sptgs; i++) {
    if(sptgs[i].className == 'dlf') {
      sptgs[i].onclick = function() { this.parentNode.innerHTML = ' '; }
    }
  }

  // gets all <input> in the form, traverse the array, if type is file, register onchange
  var inptgs = upGalery.theform.getElementsByTagName('input');
  var nr_inptgs = inptgs.length;

  for(var i=0; i<nr_inptgs; i++) {
    if(inptgs[i].type == 'file') {
      inptgs[i].onchange = function() { upGalery.checkExt(this); }
    }
  }
 }
 // method to check extension of files for upload
 upGalery.checkExt = function(fup) {
   gosbmt = 1;         // set the vale of "gosbmt" to can upload
  fup.style.background = '#ffffff';         // remove background (defined when error))
  // get the file name and split it to separe the extension
  var name = fup.value;
  var ar_name = name.split('.');

  // for IE - separe dir paths (\) from name
  var ar_nm = ar_name[0].split('\\');
  for(var i=0; i<ar_nm.length; i++) var nm = ar_nm[i];

  // check the file extension
  var re = 0;
  for(var i=0; i<alowext[gal].length; i++) {
    if(alowext[gal][i] == ar_name[1]) {
      re = 1;
      break;
    }
  }

  // if re not 1, the extension isn't in the allowed list
  if(re != 1) {
    // delete the file name, color the background field, alert error
    fup.value = '';
    fup.style.background = '#feedda';
    alert(eror_ext+gal);
  }
}
 // register events to elements in form
 upGalery.regEvents = function() {
   // disable upload Submit, hides <select> list, upload fields, and button to add new boxes
   document.getElementById('sbmt').setAttribute('disabled', 'disabled');
   document.getElementById('gc').style.display = 'none';
   document.getElementById('upfields').style.display = 'none';
   document.getElementById('newup').style.display = 'none';
 }

// for radio buttons
document.getElementById('rgli').onclick = function() { upGalery.setSelect(this.value); };      // images
document.getElementById('rgla').onclick = function() { upGalery.setSelect(this.value); };      // audio

// register onsubmit to submit form, and onclick to button that adds new fields for upload
upGalery.theform.onsubmit = function() { return upGalery.uploading(); }
document.getElementById('newup').onclick = upGalery.newUpload;
upGalery.regEvents();