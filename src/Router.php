<?php

namespace RouterRequest;

class Router {
  public $routerSet;

  function __construct($routesPathFile = 'app/config/router') {
    $this -> routerSet = new RouterSet();
    $arrayRoutes = include $routesPathFile . '.php';
    $this -> routerSet -> addArray($arrayRoutes);
  }

  public function run($url, $config) {
    $data = $this -> routerSet -> match($url);
    if(isset($data['_controller'], $data['_action'])) {
  		$controller = $data['_controller'].'Controller';
  		$action = $data['_action'].'Action';

  		$controllerObject = new $controller;
  		$finF = function() use ($controllerObject, $action, $data){
  			$controllerObject -> $action($data['matches']);
  		};
    	if(isset($data['middleware'])){
  	  	$middlewareConfig = $config;
  	  	$middle = $data['middleware'];
  	  	$middle = array_reverse($middle);
  	  	$middlewareList = (new Middleware($finF));
  	  	foreach($middle as $funct){
  	  		$parking = new Middleware($middlewareConfig[$funct], $data['matches'], $middlewareList);
  	  		$middlewareList = $parking;
  	  	}
  	  	$f = $middlewareList -> funct;
  	  	$f($data, $middlewareList);
    	}
    	else
    		$finF();
      return true;
    }
    else {
      return false;
    }
  }
}
