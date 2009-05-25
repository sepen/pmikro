<?php

class Filesystem {

  var $debug = 0;

  function Filesystem($debug = 0) {
    if (!empty($debug)) $this->debug = $debug;
    return true;
  }

  function ls($path, $excludes = array()) {
    $files = array();
    $dirs = array();
    if ($directory = opendir($path)) {
      while ($f = readdir($directory)) {
        if ($this->debug) echo "f: $f<br />\n";
        $fpath = $path."/".$f;
        if ($f != "." && $f != "..") {
          if (!in_array($f, $excludes)) {
            if (is_dir($fpath)) {
              array_push($dirs, $f);
            }
            elseif (is_file($fpath)) {
              array_push($files, $f);
            }
          }
        }
      }
      closedir($directory);
    }
    return array_merge($dirs, $files);
  }

}

?>
