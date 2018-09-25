<?php

namespace RouterRequest;

class Middleware {

	public $next;
	public $data;
	public $funct;

	public function __construct($funct,$data = null, Middleware $next = null){
		$this -> funct = $funct;
		$this -> next = $next;
		$this -> data = $data;
	}

	function exec(){
		if($this -> next != null){
			$f = $this -> next -> funct;
			$f($this -> data, $this -> next);
		}
	}
}
