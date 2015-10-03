<?php

/**
 * Class rootController
 */
Class rootController extends pmikro implements iPmikroController {

    public static function getRoot() {

        $static_dir = self::$appDir . '/views/static';
        $config_dir = self::$appDir . '/config';

        $template = new Template('layout');

        $navpanel = require($config_dir . '/navpanel.php');
        $template->setVars(['navpanel' => $navpanel]);
        $template->setVars(['contents' => file_get_contents($static_dir . '/home.html')]);

        self::$appOutput = $template->getContents();
        self::out();
    }

    public static function getTest() {
        self::$appOutput = file_get_contents(self::$appDir . '/views/static/test.html');
        self::out();
    }
}
