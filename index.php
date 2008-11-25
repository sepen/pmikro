<?php

// ----------------------------------------------------------------------------
// EDIT:
$url = 'http://mikeux.dyndns.org/~sepen/pmikro';
// ----------------------------------------------------------------------------

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

// ----------------------------------------------------------------------------
// EDIT:
$template = new Template('layout');
$template->setvars(array(
  'TITLE'    => 'pmikro',
  'META'     => '<meta name="GENERATOR" content="pmikro" />',
  'CSS'      => $url.'/styles/pmikro.css',
  'HEADER'   => '<img src="'.$url.'/images/pmikro.jpg" alt="" />',
  'NAVPANEL' => array(
    'main'  => $url.'/index.php',
    'other' => $url.'/other.php'
  )  
));

$main = new Template('main');
$main->setvars(array(
  'MAIN_CONTENTS' => '*** put here your contents for the MAIN section ***<br />'
));

$template->setvars(array(
  'CONTENTS' => $main->getContents()
));
$template->show();
// ----------------------------------------------------------------------------

?>
