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