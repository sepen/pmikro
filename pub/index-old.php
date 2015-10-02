<?php

$uri = $_SERVER['REQUEST_URI'];
$base_uri = substr($uri, 0, strrpos($uri, '/')) . "/";
$base_dir = __DIR__ . '/../';

# include every library function or class in vendor directory
if ($fdir = opendir($base_dir . '/app/vendor')) {
    while ($f = readdir($fdir)) {
        if ($f != "." && $f != ".." && is_file($base_dir . '/app/vendor/'.$f)) {
            require_once($base_dir . '/app/vendor/'.$f);
        }
    }
    closedir($fdir);
}
else {
    die('ERROR: can not open vendor directory');
}

$static_dir = $base_dir . '/app/view/static';
$config_dir = $base_dir . '/app/config';

$template = new Template('layout');
$template->setVars(array('HEAD'     => file_get_contents($static_dir.'/head.html'),
                         'HEADER'   => file_get_contents($static_dir.'/header.html'),
                         'CONTENTS' => file_get_contents($static_dir.'/home.html'),
                         'FOOTER'   => file_get_contents($static_dir.'/footer.html')));

include($config_dir.'/navpanel.php'); # include navpanel array

$template->setVars(array('NAVPANEL' => $navpanel));
$template->show();
