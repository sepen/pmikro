<?php

/*
 * Template engine which do
 *
 * - Valid for static templates (html, xml, css, json, txt, md, ...)
 * - Render operations are based on regular expressions
 *
 * Syntax rules have to follow this guidelines:
 *
 * - Items to substitute delimited by {{ and }}
 * - Delimiter structures like loop at the begin of each line
 *
 */

/**
 * Class Template
 */
class Template {

    protected $templateName;
    protected $templateType;
    protected $templateFile;
    protected $templateVars = [];
    protected $templateOutput;

    /**
     * @param string $name
     * @param string $type
     */
    function __construct($name = 'index', $type = 'html') {
        $this->templateName = $name;
        $this->templateType = $type;
        $this->templateFile = pmikro::$appDir . '/views/templates/' . $this->templateName . '.template.' . $this->templateType;
        if (!file_exists($this->templateFile)) {
            die('ERROR: template does not exists '. $this->templateFile);
        }
        $this->templateOutput = file_get_contents($this->templateFile);
    }

    /**
     * @param array $vars
     */
    public function setVars($vars = []) {
        $this->templateVars = array_merge($this->templateVars, $vars);
    }

    /**
     * @return array
     */
    public function getVars() {
        return $this->templateVars;
    }

    /**
     *
     */
    public function printVars() {
        echo "<pre>\n";
        print_r($this->templateVars);
        echo "<pre><br />\n";
    }

    /**
     * @return string
     */
    public function getContents() {
        $this->templateOutput = $this->render($this->templateOutput, $this->templateVars);
        return $this->templateOutput;
    }

    /**
     * @param $contents
     */
    public function setContents($contents) {
        $this->templateOutput = $contents;
    }

    /**
     *
     */
    public function printContents() {
        $this->templateOutput = $this->render($this->templateOutput, $this->templateVars);
        print $this->templateOutput;
    }

    /**
     * @param $contents
     * @param $vars
     *
     * @return mixed
     */
    public function render($contents, $vars) {
        $patterns = [];
        $replaces = [];
        // first render rules which are not dependent on vars
        $contents = $this->renderInclude($contents);
        foreach ($vars as $key=>$value) {
            if (is_array($value)) {
                // render rules for arrays
                $contents = $this->renderLoop($contents, [$key => $value]);
            }
            else {
                array_push($patterns, '#{{ ' . $key . ' }}#');
                array_push($replaces, $value);
            }
        }
        return preg_replace($patterns, $replaces, $contents);
    }

    /**
     * @param $contents
     * @param $vars
     *
     * @return mixed
     */
    private function renderLoop($contents, $vars) {
        foreach ($vars as $varKey=>$varValue) {
            $regex = '#{% for '.$varKey.' %}((?:[^[]|{%(?!end?for '.$varKey.' %})|(?R))+){% endfor '.$varKey.' %}#';
            preg_match_all($regex, $contents, $matches);
            foreach ($matches[1] as $matchKey => $matchValue) {
                $tmpContents = '';
                foreach ($varValue as $value) {
                    $tmpLine = $matchValue;
                    foreach ($value as $k => $v) {
                        $tmpLine = $this->render($tmpLine, [$k => $v]);
                    }
                    $tmpContents .= $tmpLine;
                }
                $contents = preg_replace($regex, $tmpContents, $contents);
            }
        }
        return $contents;
    }

    private function renderInclude($contents) {
        $regex = '#{% include \'((\S)*)\' %}#';
        preg_match_all($regex, $contents, $matches);
        foreach ($matches[1] as $key=>$value) {
            $includeFile = pmikro::$appDir . '/views/templates/' . $value;
            if (file_exists($includeFile)) {
                $fileContents = file_get_contents($includeFile);
                $contents = preg_replace('#{% include \''.$value.'\' %}#', $fileContents, $contents);
            }
        }
        return $contents;
    }
}
