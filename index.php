<?php

# EDIT $url to fit your needs
# ----------------------------------------------------------------------------
$url = 'http://CHANGE.ME';
# ----------------------------------------------------------------------------

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

# EDIT this part to fit your needs
# note that you must respect the varible names you used in the layout selected
# ----------------------------------------------------------------------------
$template = new Template('layout');
$template->setVars(array(
  'HEAD' => file_get_contents('includes/head.html'),
  'HEADER' => file_get_contents('includes/header.html'),
  'CONTENTS' => file_get_contents('includes/home.html'),
  'FOOTER' => file_get_contents('includes/footer.html')
));
include('includes/navpanel.php'); # include navpanel array
$template->setVars(array('NAVPANEL' => $navpanel));
$template->show();
# ----------------------------------------------------------------------------

?>
