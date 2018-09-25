<?php

use PHPUnit\Framework\TestCase;
use RouterRequest\Route;

final class RouteTest extends TestCase {
  public function testCanMatch1(){
    $nome = 'test1';
    $pattern = '/test1';
    $opzioni = ['opzione1', 'opzione2'];
    $route = new Route($nome, $pattern, $opzioni);
    $this -> assertSame([0 => 'opzione1', 1 => 'opzione2', 'matches' => ['/test1']]
    , $route -> match('/test1'));
  }
  /**
    @depends testCanMatch1
  */
  public function testCanMatch2(){
    $nome = 'test2';
    $pattern = '/test2-{id:[1,2,3]}';
    $opzioni = ['opzione1', 'opzione2'];
    $route = new Route($nome, $pattern, $opzioni);
    $this -> assertSame([0 => 'opzione1', 1 => 'opzione2', 'matches' => ['/test2-1', 'id'=>'1', '1', '1']]
    , $route -> match('/test2-1'));
    $this -> assertSame([0 => 'opzione1', 1 => 'opzione2', 'matches' => ['/test2-2', 'id'=>'2', '2', '2']]
    , $route -> match('/test2-2'));
    $this -> assertSame([0 => 'opzione1', 1 => 'opzione2', 'matches' => ['/test2-3', 'id'=>'3', '3', '3']]
    , $route -> match('/test2-3'));
  }
  /**
    @depends testCanMatch2
  */
  public function testComplicatedPattern1() {

      $nome = 'test3';
      $pattern = '/test3-{id:[0-9]}';
      $opzioni = ['opzione1', 'opzione2'];
      $route = new Route($nome, $pattern, $opzioni);
      $ris = $route -> match('/test3-5');
      $this -> assertSame('5', $ris['matches']['id']);

      $pattern = '/test3-{id:[0-9]*}';
      $route = new Route($nome, $pattern, $opzioni);
      $ris = $route -> match('/test3-123456');
      $this -> assertSame('123456', $ris['matches']['id']);

      $pattern = '/test3-{id:(dd)?[0-9]*}';
      $route = new Route($nome, $pattern, $opzioni);
      $ris = $route -> match('/test3-dd123456');
      $this -> assertSame('dd123456', $ris['matches']['id']);
  }
  /**
    @depends testComplicatedPattern1
  */
  public function testComplicatedPattern2() {

      $nome = 'test3';
      $pattern = '/test3-{id:[0-9]kk(dd)?_[0-9]*}';
      $opzioni = ['opzione1', 'opzione2'];
      $route = new Route($nome, $pattern, $opzioni);

      $ris = $route -> match('/test3-5kk_123');
      $this -> assertSame('5kk_123', $ris['matches']['id']);

      $ris = $route -> match('/test3-5kkdd_123');
      $this -> assertSame('5kkdd_123', $ris['matches']['id']);

  }
}
