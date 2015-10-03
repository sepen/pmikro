<?php

/*
 * Template engine which do
 *
 * - Valid for static templates (html, xml, css, json, txt, md, ...)
 * - Replaces contents based on associative arrays passed as arguments
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
        $this->render();
        return $this->templateOutput;
    }

    /**
     *
     */
    public function printContents() {
        $this->render();
        print $this->templateOutput;
    }

    /**
     *
     */
    private function render() {
        $patterns = [];
        $replaces = [];
        foreach ($this->templateVars as $key=>$value) {
            if (is_array($value)) {
                $this->renderLoop($key, $value);
            } else {
                array_push($patterns, '/{{' . $key . '}}/');
                array_push($replaces, $value);
            }
        }
        $this->templateOutput = preg_replace($patterns, $replaces, $this->templateOutput);
    }

    /**
     * @param $key
     * @param $value
     */
    private function renderLoop($key, $value) {
        $regexp = '/{{loop: ' . $key . '}}\n'
            . '(.*\n)*'
            . '{{end_loop: ' . $key . '}}/';
        preg_match($regexp, $this->templateOutput, $matches);
        foreach($matches as $matchedLine) {
            $loop = preg_replace('/{{loop: ' . $key . '}}/', '', $matchedLine);
            $loop = preg_replace('/{{end_loop: ' . $key . '}}/', '', $loop);
            $tmp = '';
            foreach ($value as $va) {
                $loop_tmp = $loop;
                foreach ($va as $k=>$v) {
                    $loop_tmp = $this->renderVar($loop_tmp, $k, $v);
                }
                $tmp .= $loop_tmp;
            }
            $this->templateOutput = preg_replace($regexp, $tmp, $this->templateOutput);
        }
    }

    /**
     * @param $contents
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    private function renderVar($contents, $key, $value) {
        return preg_replace('/{{' . $key . '}}/', $value, $contents);
    }
}
