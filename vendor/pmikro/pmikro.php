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

        (isset(self::$appRequestArray[0]) && self::$appRequestArray[0] != '') ? $route_0 = self::$appRequestArray[0] : $route_0 = 'root';
        $routeFile = self::$appDir . '/routes/' . $route_0 . '.php';

        if (is_file($routeFile)) {
            require_once($routeFile);
            (isset(self::$appRequestArray[1]) && self::$appRequestArray[1] != '') ? $route_1 = self::$appRequestArray[1] : $route_1 = 'root';
        }
        else {
            $route_1 = $route_0;
            $route_0 = 'root';
        }
        $routeAction = strtolower(self::$appRequestMethod) . ucfirst(strtolower($route_1));

        // check if is a valid route
        if (in_array('iPmikro', class_implements($route_0))) {
            // check function exists in class
            if (in_array($routeAction, get_class_methods($route_0))) {
                return $route_0::$routeAction();
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
     * @param $code
     */
    public static function error($code) {
        echo file_get_contents(self::$appDir . '/views/static/error404.html');
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
            $backArray = defined('DEBUG_BACKTRACE_IGNORE_ARGS') ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) : debug_backtrace(false);
            $message = $message . "\r\n" . print_r($backArray, true);
        }
        fwrite($fd, $timeStamp . " -- " . $message . "\r\n");
        fclose($fd);
    }
}

/**
 * Interface iPmikro
 */
Interface iPmikro {

    public static function getRoot();
}

