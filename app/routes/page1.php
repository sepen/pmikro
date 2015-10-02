<?php

/**
 * Class page1Controller
 */
Class page1Controller extends pmikro implements iPmikroController {

    public static function getRoot() {
        self::$appOutput = '<html><body style="margin: 20px 0; text-align: center">'
            . '<h1>Test page</h1><hr><p>Request: GET /page1</p>'
            . '<p>Source: routes/page1.php (getRoot)</p></body></html>';
        self::out();
    }

    public static function getFoo() {
        self::$appOutput = '<html><body style="margin: 20px 0; text-align: center">'
            . '<h1>Test page</h1><hr><p>Request: GET /page1/foo</p>'
            . '<p>Source: routes/page1.php (getFoo)</p></body></html>';
        self::out();
    }

    public static function getExamplejson() {
        $template = new Template('example', 'json');
        $template->setVars(['EXAMPLE_VALUE' => 'This is an example of substituted value']);
        self::$appOutput = $template->getContents();
        self::out();
    }
}
