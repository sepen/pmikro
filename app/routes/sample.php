<?php

/**
 * Class sampleController
 */
Class sampleController extends pmikro implements iPmikroController {

    // Response for request http://example.domain/sample
    public static function getRoot() {
        self::$appOutput = '<html><head><title>raw</title></head><body>'
            . '<h1>raw</h1>'
            . '<div>Sample page generated from <code>/routes/sample.php</code></div>'
            . '<div>and <code>/views/static/sample.html</code></div>'
            . '</body></html>';
        self::out();
    }

    // Response for request http://example.domain/sample/sub1
    public static function getSub1() {
        $template = new Template('sample', 'json');
        $template->setVars([
            'EXAMPLE_KEY1' => 'example_value1',
            'EXAMPLE_KEY2' => 'example_value2'
        ]);
        self::$appOutput = $template->getContents();
        self::out();
    }
}
