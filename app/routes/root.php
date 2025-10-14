<?php

/**
 * Class rootController
 *
 * This class handles the routing for the root and top level pages of the application
 */
Class rootController extends pmikro implements iPmikroController {

    // Response for request http://example.domain/
    public static function getRoot() {
        // Set the navigation panel
        $navpanel = require(self::$appDir.'/config/navpanel.php');

        // Set the contents of the page
        $contents = file_get_contents(self::$appDir.'/views/static/index.html');

        // Create a new Template object and set the variables for the template
        $template = new Template('layout');
        $template->setVars(['navpanel' => $navpanel]);
        $template->setVars(['contents' => $contents]);

        /// Render the template
        self::$appOutput = $template->getContents();
        self::out();
    }

    // Response for request http://example.domain/sub1
    public static function getSub1() {
        // Set the navigation panel
        $navpanel = require(self::$appDir.'/config/navpanel.php');

        // Set the contents of the page
        $markdown = file_get_contents(self::$appDir.'/views/static/sample.md');
        $mdparser = new MarkdownParser();
        $contents = $mdparser->parse($markdown);

        // Create a new Template object and set the variables for the template
        $template = new Template('layout');
        $template->setVars(['navpanel' => $navpanel]);
        $template->setVars(['contents' => $contents]);

        // Render the template
        self::$appOutput = $template->getContents();
        self::out();
    }

    // Response for request http://example.domain/sub2
    public static function getSub2() {
        self::$appOutput = '<html><head><title>HTML Example</title></head><body>'
            . '<h1>HTML ExampleL</h1>'
            . '<div>Sample page generated from <code>/routes/root.php</code></div>'
            . '<div>and <code>function getExamplehtml()</code></div>'
            . '</body></html>';
        self::out();
    }

}
