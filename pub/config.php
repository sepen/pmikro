<?php

$uri = $_SERVER['REQUEST_URI'];
$base_uri = substr($uri, 0, strrpos($uri, '/'))."/";

# include every library function or class in libs directory
if ($fdir = opendir('libs')) {
  while ($f = readdir($fdir)) {
    if ($f != "." && $f != "..") {
      $lib = 'libs/'.$f;
      if (is_file($lib)) include($lib);
    }
  }
  closedir($fdir);
}
else {
  echo "Error, can't open 'libs' directory<br />\n";
  exit;
}

?>
