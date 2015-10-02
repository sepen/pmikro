<?php

include('config.php');

$template = new Template('layout');

$template->setVars(array('HEAD'     => file_get_contents('includes/head.html'),
                         'HEADER'   => file_get_contents('includes/header.html'),
                         'CONTENTS' => file_get_contents('includes/home.html'),
                         'FOOTER'   => file_get_contents('includes/footer.html')));

include('includes/navpanel.php'); # include navpanel array

$template->setVars(array('NAVPANEL' => $navpanel));
$template->show();
