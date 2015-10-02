<?php

/**
 * Class pmikro
 */
Class pmikro {

    public static $appMode;
    public static $appDir;
    public static $appLogFile;
    public static $appRequestMethod;
    public static $appRequestUri;
    public static $appRequestArray;
    public static $appOutput;

    /**
     *
     */
    public static function start() {
        self::$appMode = php_sapi_name();
        self::$appDir = $_SERVER['DOCUMENT_ROOT'] . '/../app';
        self::$appLogFile = self::$appDir.'/app.log';
        self::$appRequestMethod = $_SERVER['REQUEST_METHOD'];
        self::$appRequestUri = $_SERVER['REQUEST_URI'];
        self::$appRequestArray = explode('/', rtrim(ltrim(self::$appRequestUri, '/'), '/'));

        self::autoload();
        self::route();
    }

    /**
     * @param string $uri
     *
     * @return mixed
     */
    public static function get($uri = '/') {
        self::$appRequestArray = explode('/', $uri);

        return self::route();
    }

    /**
     * Handles autoloading of classes.
     */
    protected static function autoload() {
        $loadDirs = [
            self::$appDir . '/../vendor/pmikro',
            self::$appDir . '/routes'
        ];

        foreach($loadDirs as $loadDir) {
            if ($lDir = opendir($loadDir)) {
                while ($f = readdir($lDir)) {
                    if ($f != "." && $f != ".." && is_file($loadDir . '/' . $f)) {
                        require_once($loadDir . '/' . $f);
                    }
                }
                closedir($lDir);
            }
        }
    }

    /**
     * @return mixed
     */
    protected static function route() {

        $routeArray = ['root', 'root'];

        if (isset(self::$appRequestArray[0]) && self::$appRequestArray[0] != '') {
            $routeArray[0] = self::$appRequestArray[0];
        }
        $routeFile = self::$appDir . '/routes/' . $routeArray[0] . '.php';

        if (is_file($routeFile)) {
            require_once($routeFile);
            if (isset(self::$appRequestArray[1]) && self::$appRequestArray[1] != '') {
                $routeArray[1] = self::$appRequestArray[1];
            }
        }
        else {
            $routeArray[1] = $routeArray[0];
            $routeArray[0] = 'root';
        }
        $routeClass = strtolower($routeArray[0]) . 'Controller';
        $routeAction = strtolower(self::$appRequestMethod) . ucfirst(strtolower($routeArray[1]));

        // check if is a valid route
        if (in_array('iPmikroController', class_implements($routeClass))) {
            // check function exists in class
            if (in_array($routeAction, get_class_methods($routeClass))) {
                return $routeClass::$routeAction();
            }
            else {
                self::error(404);
            }
        }
        else {
            self::error(404);
        }
    }

    /**
     *
     */
    public static function out() {
        echo self::$appOutput;
    }

    /**
     * @param $errorCode
     */
    public static function error($errorCode) {
        echo file_get_contents(self::$appDir . '/views/static/error'.$errorCode.'.html');
    }

    /**
     * @param            $message
     * @param            $value
     * @param bool|false $backTrace
     */
    public static function debug($message, $value, $backTrace = false) {
        $output = '<hr>'. $message . '<pre>' . print_r($value, true) . '</pre>';
        if ($backTrace === true) {
            $backTraceArray = defined('DEBUG_BACKTRACE_IGNORE_ARGS') ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) : debug_backtrace(false);
            foreach ($backTraceArray as $backTraceLine) {
                $output .= 'DEBUG: '
                    . $backTraceLine['class']
                    . $backTraceLine['type']
                    . $backTraceLine['function'] . '('
                    . $backTraceLine['line'] . ') '
                    . $backTraceLine['file'] . '<br>';
            }
        }
        echo $output;
    }

    /**
     * @param            $message
     * @param bool|false $backTrace
     */
    public static function log($message, $backTrace = false) {
        $fd = fopen(self::$appLogFile, 'a');
        $timeStamp = date('Y-m-d h:i:s');
        if ($backTrace === true) {
            if ($backArray = defined('DEBUG_BACKTRACE_IGNORE_ARGS')) {
                debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            }
            else {
                debug_backtrace(false);
            }
            $message = $message . "\r\n" . print_r($backArray, true);
        }
        fwrite($fd, $timeStamp . " -- " . $message . "\r\n");
        fclose($fd);
    }
}

/**
 * Interface iPmikroController
 */
Interface iPmikroController {

    public static function getRoot();
}

