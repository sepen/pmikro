<?php

session_start();

$appFile = __DIR__.'/../vendor/pmikro/pmikro.php';

if (is_file($appFile)) {
    require_once($appFile);
} else {
    die('ERROR: failed to load appFile: '.$appFile);
}

pmikro::start();