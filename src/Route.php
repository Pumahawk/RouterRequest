<?php

namespace RouterRequest;

class Route {
  public $name;
  public $pattern;
  public $options;

  function __construct($name, $pattern, $options) {
    $this -> name = $name;
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/^'.$pattern.'$/';

    $pat[0] = '/\{(?:([a-z]+):)?([^\{\}]*)\}/';
    $rep[0]= '(?<$1>($2))';
    $pattern = preg_replace($pat, $rep, $pattern);

    $this -> pattern = $pattern;
    $this -> options = $options;
  }

  public function match($string) {
    $matches;
    if(!preg_match($this -> pattern, $string, $matches))
      return false;
    else {
      $this -> options['matches'] = $matches;
      return $this -> options;
    }
  }
}
