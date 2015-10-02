<?php

/**
 * Class Template
 */
class Template {

    protected $name = '';
    protected $file = '';
    protected $contents = '';
    protected $vars = [];

    /**
     * @param string $name
     */
    function __construct($name = 'index') {
        $this->name = $name;
        $this->file = pmikro::$appDir . '/views/templates/' . $this->name . '.template.html';
        if (!file_exists($this->file)) {
            die('ERROR: template does not exists '. $this->file);
        }
        $this->contents = file_get_contents($this->file);
    }

    /**
     * @param array $vars
     */
    public function setVars($vars = array()) {
        $this->vars = array_merge($this->vars, $vars);
    }

    /**
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     *
     */
    public function printVars() {
        echo "<pre>\n";
        print_r($this->vars);
        echo "<pre><br />\n";
    }

    /**
     * @return string
     */
    public function getContents() {
        $this->render();
        return $this->contents;
    }

    /**
     *
     */
    public function show() {
        $this->render();
        print $this->contents;
    }

    /**
     *
     */
    protected function render() {
        $patterns = array();
        $replaces = array();
        foreach ($this->vars as $key => $value) {
            if (is_array($value)) {
                $regexp = '/<!-- loop: ' . $key . ' -->\n'
                    . '(.*\n)*'
                    . '<!-- end loop: ' . $key . ' -->/';
                preg_match($regexp, $this->contents, $m);
                if (isset($m[0])) {
                    $loop = $m[0];
                    $loop = preg_replace('/<!-- loop: ' . $key . ' -->/', '', $loop);
                    $loop = preg_replace('/<!-- end loop: ' . $key . ' -->/', '', $loop);
                    $ltmp = "";
                    foreach ($value as $va) {
                        $loop_tmp = $loop;
                        foreach ($va as $k => $v) {
                            $loop_tmp = preg_replace('/{{' . $k . '}}/', $v, $loop_tmp);
                        }
                        $ltmp .= $loop_tmp;
                    }
                    $this->contents = preg_replace($regexp, $ltmp, $this->contents);
                }
            } else {
                array_push($patterns, '/{{' . $key . '}}/');
                array_push($replaces, $value);
            }
        }
        $this->contents = preg_replace($patterns, $replaces, $this->contents);
    }

}
