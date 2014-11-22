<?php

class UploadGalery {
  // properties
  static protected $conn;            // stores the connection to mysql
  protected $conn_data = array();            // to store data for connecting to database
  public $gimgs = 'gimgs';             // table name that store images data
  public $gaudio = 'gaudio';           // table name that store data for audio files
  public $gal;                        // store the galery (table name) for which to perform query
  public $category;                    // store the category
  public $file;                        // to store the name of the file in which the script is used
  protected $eror = false;             // store the errors

  // constructor
  public function __construct() {
    $this->lsite = $GLOBALS['lsite'];     // store in property the text for language
    $this->conn_data = $GLOBALS['mysql'];

    // sets the values for $gal, $category, and $file properties
    $this->gal = (isset($_REQUEST['gl']) && $_REQUEST['gl'] == $this->lsite['gimgs']) ? $this->gimgs : $this->gaudio;
    $this->category = (isset($_REQUEST['gc'])) ? $_REQUEST['gc'] : '';
    $this->file = basename($_SERVER['PHP_SELF']);

    if(isset($_FILES['fup'])) echo $this->uploadFile();      // upload the files, if form submited
  }

  // for connecting to mysql, with PDO, sets the object with connection in $conn, and returns it
  public function setConn($conn_data) {
    try {
      // Connect and create the PDO object
      self::$conn = new PDO("mysql:host=".$conn_data['host']."; dbname=".$conn_data['bdname'], $conn_data['user'], $conn_data['pass']);

      // Sets to handle the errors in the ERRMODE_EXCEPTION mode
      self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      self::$conn->exec('SET CHARACTER SET utf8');      // Sets encoding UTF-8
      
    }
    catch(PDOException $e) {
      echo $this->lsite['eror_conn']. $e->getMessage();       // output the error if cannot connect
    }
    return self::$conn;
  }

  // selects data in $gal, according to $category, and return an array with the record set
  protected function selCategory($gal='', $category='') {
    if(self::$conn===NULL) $this->setConn($this->conn_data);        // sets the connection to mysql
    $rows = array();           // to store the rows to be returned

    // if there is a connection set ($conn property not false)
    if(self::$conn !== false && self::$conn!==NULL) {
      $sql = "SELECT * FROM `$gal` WHERE `category`='$category' ORDER BY `id` DESC";
      // performs the query and get returned data
      try {
        if($sqlprep = self::$conn->prepare($sql)) {
          // execute query
          if($sqlprep->execute()) {
            // if fetch() returns at least one row (not false), adds each row in $rows for return
            if(($row = $sqlprep->fetch(PDO::FETCH_ASSOC)) !== false){
              do { $rows[] = $row; }
              while($row = $sqlprep->fetch(PDO::FETCH_ASSOC));
            }
            else $this->eror[] = $this->lsite['notelm'];
          }
          else $this->eror[] = $this->lsite['eror_sqle'];
        }
        else {
          $eror = self::$conn->errorInfo();
          $this->eror[] = $eror[2];
        }
        self::$conn = null;        // Disconnect
      }
      catch(PDOException $e) {
        $this->eror = $e->getMessage();
      }
    }

    return $rows;        // returns data stored in $list
  }

  // returns HTML code with the image /audio gallery in the specified category
  public function getGallery($gal='', $category='') {
    $galery = '';           // to store the galery to be returned

    // if parameters $gal, and $category not specified, gets their data from properties
    if($gal == '') $gal = $this->gal;
    if($category == '') $category = $this->category;

    $rows = $this->selCategory($gal, $category);     // get the rows with files in specified $category
    $nrows = count($rows);

    // if not error, and at least one row, traverse the $rows, and create the HTML code for Galery, else, return error
    if($this->eror === false && $nrows > 0) {
      for($i=0; $i<$nrows; $i++) {
        $url = $gal.'/'.$rows[$i]['category'].'/'.$rows[$i]['file'];       // the path to the file
        // Sets in $galery the HTML code with data to be returned
        // if image gallery, set link to image with thumbail, else, link to audio file
        if($gal == $this->gimgs) {
          $thumb = preg_replace('/(.*?)\.(gif|jpg|jpe|png)$/i', '${1}_thmb.${2}', $url);
          $galery .= '<div class="gimgs"><b>'.$rows[$i]['title'].'</b><br/><a href="'.$url.'" title="'.$rows[$i]['title'].'" target="_blank"><img src="'.$thumb.'" alt="'.$rows[$i]['title'].'" /></a><br/>'.$rows[$i]['descript'].'</div>';
        }
        else if($gal == $this->gaudio) {
          $galery .=  '<div id="musicPl"<a href="#"><audio name="media" controls="show"><source src="http://localhost/dudu/'.$url.'"></audio><br /><div id="mInfo"> '.$rows[$i]['title'].'</a> (<i>'.date('j-M-Y, H:i', $rows[$i]['dtreg']).'</i>)<blockquote>'.$rows[$i]['descript'].'</blockquote></div></div>';
        }
      }
    }
    else $galery = implode('<br/>', $this->eror);

    return $galery;
  }

  // adds in database data for uploaded file(), $datfile is an array with data for each file
  protected function insertData($datfile, $delid) {
    if(self::$conn===NULL) $this->setConn($this->conn_data);        // sets the connection to mysql
    $reout = '';                // store data to return

    // if there is a connection set ($conn property not false)
    if(self::$conn !== false && self::$conn!==NULL) {
      $sql = "INSERT INTO `$this->gal` (`file`, `title`, `descript`, `category`, `dtreg`) VALUES ". implode(',', $datfile);
      // performs the query and return True on sccess, else output eror, and set False
      try {
        if($sqlprep = self::$conn->prepare($sql)) {
          // execute query
          if($sqlprep->execute()) {
            $reout = $this->lsite['inserted']. $this->gal;

            // if rows ID to delete, in $delid, calls delRows();
            if(count($delid) > 0) $this->delRows($delid);
          }
          else $reout = $this->lsite['inserted']. $this->gal;
        }
        else {
          $eror = self::$conn->errorInfo();
          $reout = $eror[2];
        }
      }
      catch(PDOException $e) {
        $reout = $e->getMessage();
      }
    }

    return $reout;        // returns data stored in $list
  }

  // this method Upload files, and return its data
  protected function uploadFile() {
    $dirup = '../'.$this->gal. '/'. $this->category;         // directory in which to upload the file
    $reout = array();      // Array to store data returned by this method

    // gets the Array with rules for upload file (defined in "config.php"), and allowed extensions, and maxsize
    $upfrule = $GLOBALS['upfrule'];
    $allowext = ($this->gal == $this->gimgs) ? $upfrule['imgext'] : $upfrule['audioext'];
    $maxsize = ($this->gal == $this->gimgs) ? $upfrule['imgsize'] : $upfrule['audiosize'];

    // if the folder for upload (category) exists, and is writtable, sets code for uploading, else, output error
    if(is_dir($dirup) && is_writable($dirup)) {
      // if receive valid file(s) from form
      if(isset($_FILES['fup'])) {
        // get the rows with files in the specified category, to check if uploaded file is already registered
        $rows = $this->selCategory($this->gal, $this->category);
        $nrows = count($rows);
        $files = array();         // to store registered files
        $delid = array();       // stores ID of rows to delete (that will contain duplicate 'file')

        // if not error, and at least one row, traverse the $rows, and add the file name (with its ID) in $files
        if($this->eror === false && $nrows > 0) {
          for($i=0; $i<$nrows; $i++) {
            $files[$rows[$i]['id']] = $rows[$i]['file'];
          }
        }
        $this->eror = false;         // reset errors

        $nrf = count($_FILES['fup']['name']);            // get number of files
        $datfile = array();            // store data of each file to be added in database

        // checks the files received for upload
        for($f=0; $f<$nrf; $f++) {
          $fname = $_FILES['fup']['name'][$f];       // gets the name of the file
          $fsize = $_FILES['fup']['size'][$f];       // gets the size of the file

          // checks to not be an empty field (the name of the file to have more then 1 character)
          if(strlen($fname)>3) {
            // gets file extension
            $splitimg = explode('.', strtolower($fname));
            $ext = end($splitimg);

            // checks the file to match allowed rules
            if(!in_array($ext, $allowext)) $this->eror[] = $fname. $this->lsite['eror_ext']. $_REQUEST['gl'];
            if($fsize >= ($maxsize*1000)) $this->eror[] = sprintf($this->lsite['eror_upmaxsize'], $fname, $maxsize);

            // if upload an image, checks its dimensions
            if($this->gal == $this->gimgs) {
              list($width, $height) = getimagesize($_FILES['fup']['tmp_name'][$f]);     // gets image width and height
              if($width > $upfrule['width'] || $height > $upfrule['height']) {
                $this->eror[] = sprintf($this->lsite['eror_upimgwh'], $fname, $width.'x'.$height, $upfrule['width'].'x'.$upfrule['height']);
              }
            }

            // if no error, performs Upload
            if($this->eror === false) {
              if(move_uploaded_file($_FILES['fup']['tmp_name'][$f], $dirup.'/'.$fname)) {
                // if the file exists in database, adds its ID to delete, after insert to keep the new record
                if(in_array($fname, $files)) $delid[] = array_search($fname, $files);

                // define data to be added in database
                $datfile[] = "('".$fname."', '".trim(strip_tags($_POST['title'][$f]))."', '".trim(strip_tags($_POST['descript'][$f]))."', '".$this->category."', ".time().")";

                // create the thumbail with height defined to 'hthmb' (in upgalery.php), and width proportioned to 'hthmb'
                if($this->gal == $this->gimgs) $this->makeThumb($dirup.'/'.$fname, $ext, 0, $upfrule['hthmb']);
/*
 To create the thumbail with the specified width, and height proportioned to it, replace the instruction above with this code:
    if($this->gal == $this->gimgs) $this->makeThumb($dirup.'/'.$fname, $ext, $upfrule['wthmb'], 0);
 To create the thumbail with width and height fixed, replace the instruction above with this code:
    if($this->gal == $this->gimgs) $this->makeThumb($dirup.'/'.$fname, $ext, $upfrule['wthmb'], $upfrule['hthmb']);
*/
                $reout[] = sprintf($this->lsite['uploaded'], $fname).' / '.($fsize /1000).' KB';     // add file data to return
              }
            }
            else { $reout[] = implode('<br/>', $this->eror); $this->eror = false; }  // store errors, and reset $eror
          }
        }

        //if data in $datfile, sends data to be aded in database ($delid to delete), add returned vale in $reout
        if(count($datfile) > 0) $reout[] = $this->insertData($datfile, $delid);
      }
    }
    else $reout[] = sprintf($this->lsite['eror_dir'], $dirup);

    return implode('<br/>', $reout);
  }

  // create the Thumbail for image (in $fimg), with dimensions proportionally to $hthmb
  public function makeThumb($fimg, $ext, $wthmb=0, $hthmb=0) {
    // get the image object, according to $ext (extension)
    if($ext == 'gif') $img = imagecreatefromgif($fimg);
    else if($ext == 'jpe' || $ext == 'jpg') $img = imagecreatefromjpeg($fimg);
    else if($ext == 'png') $img = imagecreatefrompng($fimg);

    // get image size and define new width, proportionally to $hthmb
    list($width, $height) = getimagesize($fimg);
    if($wthmb == 0) $wthmb = $width * $hthmb / $height;
    if($hthmb == 0) $hthmb = $height * $wthmb / $width;

    // create the thumbail, resize, and save the thumbail (according to $ext)
    $thumb = imagecreatetruecolor($wthmb, $hthmb);
    $thumbname = preg_replace('/(.*?)\.(gif|jpg|jpe|png)$/i', '${1}_thmb.${2}', $fimg);
    imagecopyresized($thumb, $img, 0, 0, 0, 0, $wthmb, $hthmb, $width, $height);

    if($ext == 'gif') imagegif($thumb, $thumbname);
    else if($ext == 'jpe' || $ext == 'jpg') imagejpeg($thumb, $thumbname);
    else if($ext == 'png') imagepng($thumb, $thumbname);

    imagedestroy($thumb);             // Free up memory
  }

  // Returns an Array with categories (folders) in specified $dir
  protected function getCategs($dir) {
    $categs = array();            // to store the categories
    $dirobj = new DirectoryIterator($dir);         // object of the dir

    // traverse the $dirobj
    foreach($dirobj as $fileobj) {
      // if the curent item is a directory (but not . or ..), add it in $menu, as category
      if($fileobj->isDir() && !$fileobj->isDot()) {
        $categs[] = $fileobj->getFilename();
      }
    }
    return $categs;
  }

  // Returns a menu with categories (folders) in specified $dir
  public function getMenu($dir) {
    $categs = $this->getCategs($dir);         // get the categories
    $nrc = count($categs);
    $menu = '<ul>';            // starts the menu

    // traverse the $categs, and create in $menu the list width categories
    for($i=0; $i<$nrc; $i++) {
      $menu .= '<li><a href="'.$this->file.'?gl='.$this->lsite[$dir].'&amp;gc='.$categs[$i].'" title="'.$categs[$i].'">'.ucfirst($categs[$i]).'</a></li>';
    }
    $menu .= '</ul>';            // close the Menu
    return $menu;
  }

  // Returns <option> with categories in specified $dir (to be added with JavaScript in <select>)
  public function getOptions($dir) {
    $categs = $this->getCategs($dir);         // get the categories
    $nrc = count($categs);
    $option = '<option>---</option>';            // start <option> list

    // traverse the $categs, and create in $option elements width categories
    for($i=0; $i<$nrc; $i++) {
      $option .= '<option value="'.$categs[$i].'">'.ucfirst($categs[$i]).'</option>';
    }
    return $option;
  }

  // to delete rows with duplicate name in 'file', in same 'category' (their ID is receved in $delid, an array)
  protected function delRows($delid) {
    if(self::$conn===NULL) $this->setConn($this->conn_data);        // sets the connection to mysql
    try {
      $sql = "DELETE FROM `$this->gal` WHERE `id` IN(".implode(',', $delid).")";
      self::$conn->exec($sql);

      self::$conn = null;        // Disconnect
    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
  }
}