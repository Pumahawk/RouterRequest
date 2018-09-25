<?php

namespace RouterRequest;

class RouterSet {
  public $routeList = array();

  public function add(Route $route) {
    $this ->routeList[] = $route;
  }

  public function addArray($routeArray, $basePattern = '', $middleware = array()) {
    foreach ($routeArray as $k => $route) {
  	$middlewareSupport = array();
    	if(isset($route['group'])){
    		$this -> addArray($route,$basePattern.$route['group'], (isset($route['middleware'])) ? array_merge($route['middleware'], $middleware) : $middleware);
      }
      if(($k != 'group' && $k != 'middleware') || $k == 0){
    		if(isset($route['options']['middleware'])){
				      $middlewareSupport = $route['options']['middleware'];
    		}

    		if(isset($route['options'])){
    			$route['options']['middleware'] = array_merge($middleware,$middlewareSupport);
      		$this -> add(new Route($route['name'], $basePattern.$route['pattern'], $route['options']));
    	  }
    	}
    }
  }

  public function match($string) {
    foreach ($this -> routeList as $route) {
      if ($r = $route -> match($string)) {
        return $r;
      }
    }

    return false;
  }
}
