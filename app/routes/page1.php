<?php

Class page1 extends pmikro implements iPmikro {

    public static function getRoot() {
        self::$appOutput = 'page1 root page (GET /page1/)';
        self::out();
    }

    public static function getFoo() {
        //self::$appOutput = 'page1 foo page (GET /page1/foo)';
        self::$appOutput = implode('', file('http://www.php.net/'));
        self::out();
    }
}
