<?php

class Template {
    
  var $name;
  var $file;
  var $contents;
  var $vars = array();
    
  function Template($name = "index") {
    $this->name = $name;
    $this->file = 'templates/'.$name.'.thtml';
    if (!file_exists($this->file)) {
      echo "Error, file '".$this->file."' not exists<br />\n";
      return false;
    }
    $this->contents = file_get_contents($this->file);
  }
    
  function setVars($vars = array()) {
    $this->vars = array_merge($this->vars, $vars);
  }

  function getVars() {
    return $this->vars;
  }
    
  function printVars() {
    echo "<pre>\n"; print_r($this->vars); echo "<pre><br />\n";
  }

  function render() {
    $patterns = array(); $replaces = array();
    foreach ($this->vars as $key=>$value) {
      if (is_array($value)) {
        $regexp = '/<!-- loop: '.$key.' -->\s?\n?(\s*.*?\n?)\s*<!-- end loop -->/';
        preg_match_all($regexp, $this->contents, $m);
        $loop = $m[1][0];
        $ltmp = '';
        foreach($value as $k=>$v) {
          $aux = preg_replace('/{{'.$key.'\.KEY}}/', $k, $loop);
          $ltmp.= preg_replace('/{{'.$key.'.VALUE}}/', $v, $aux);
        }
        $this->contents = preg_replace($regexp, $ltmp, $this->contents);
      }
      else {
        array_push($patterns, '/{{'.$key.'}}/');
        array_push($replaces, $value);
      }
    }
    $this->contents = preg_replace($patterns, $replaces, $this->contents);
  }

  function getContents() {
    $this->render();
    return $this->contents;
  }

  function show() {
    $this->render();
    print $this->contents;
  }

}

?>
