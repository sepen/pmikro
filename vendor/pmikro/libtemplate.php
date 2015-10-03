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
        foreach ($this->templateVars as $key => $value) {
            if (is_array($value)) {
                $regexp = '/{{loop: ' . $key . '}}\n'
                    . '(.*\n)*'
                    . '{{end_loop: ' . $key . '}}/';
                preg_match($regexp, $this->templateOutput, $m);
                if (isset($m[0])) {
                    $loop = $m[0];
                    $loop = preg_replace('/{{loop: ' . $key . '}}/', '', $loop);
                    $loop = preg_replace('/{{end_loop: ' . $key . '}}/', '', $loop);
                    $ltmp = "";
                    foreach ($value as $va) {
                        $loop_tmp = $loop;
                        foreach ($va as $k => $v) {
                            $loop_tmp = preg_replace('/{{' . $k . '}}/', $v, $loop_tmp);
                        }
                        $ltmp .= $loop_tmp;
                    }
                    $this->templateOutput = preg_replace($regexp, $ltmp, $this->templateOutput);
                }
            } else {
                array_push($patterns, '/{{' . $key . '}}/');
                array_push($replaces, $value);
            }
        }
        $this->templateOutput = preg_replace($patterns, $replaces, $this->templateOutput);
    }

}
