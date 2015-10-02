<?php

Class root extends pmikro implements iPmikro {

    public static function getRoot() {

        $static_dir = self::$appDir . '/view/static';
        $config_dir = self::$appDir . '/config';

        $template = new Template('layout');
        $template->setVars(array('HEAD'     => file_get_contents($static_dir.'/head.html'),
                                 'HEADER'   => file_get_contents($static_dir.'/header.html'),
                                 'CONTENTS' => file_get_contents($static_dir.'/home.html'),
                                 'FOOTER'   => file_get_contents($static_dir.'/footer.html')));

        include($config_dir.'/navpanel.php'); # include navpanel array

        $template->setVars(array('NAVPANEL' => $navpanel));
        $template->render();

        self::$appOutput = $template->getContents();
        self::out();
    }

    public static function getTest() {
        self::$appOutput = file_get_contents(self::$appDir . '/view/static/test.html');
        self::out();
    }
}
