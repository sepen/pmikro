<?php

/**
 * Class rootController
 */
Class rootController extends pmikro implements iPmikroController {

    public static function getRoot() {
        $navpanel = require(self::$appDir.'/config/navpanel.php');

        $template = new Template('layout');
        $template->setVars(['navpanel' => $navpanel]);
        $template->setVars(['contents' => file_get_contents(self::$appDir.'/views/static/home.html')]);

        self::$appOutput = $template->getContents();
        self::out();
    }

    public static function getTest() {
        $navpanel = require(self::$appDir.'/config/navpanel.php');

        $template = new Template('layout');
        $template->setVars(['navpanel' => $navpanel]);
        $template->setVars(['contents' => file_get_contents(self::$appDir.'/views/static/test.html')]);

        self::$appOutput = $template->getContents();
        self::out();
    }
}
