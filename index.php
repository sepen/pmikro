<?php

# ----------------------------------------------------------------------------
$url = 'http://mikeux.dyndns.org/pmikro';
# ----------------------------------------------------------------------------

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

# ----------------------------------------------------------------------------
$template = new Template('layout');
$template->setVars(array(
  'HEAD' => file_get_contents('includes/head.html'),
  'HEADER' => file_get_contents('includes/header.html'),
  'CONTENTS' => file_get_contents('includes/example.html'),
  'FOOTER' => file_get_contents('includes/footer.html')
));
include('includes/navpanel.php'); # load $navpanel variable
$template->setVars(array('NAVPANEL' => $navpanel));
$template->show();
# ----------------------------------------------------------------------------

?>
