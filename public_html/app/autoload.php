<?php

spl_autoload_register(function($class_name){
  $dirs = preg_split("/\\\/", $class_name);
  if ( is_array($dirs) ){
    $class_path = __DIR__ . "/../" . implode("/", $dirs) . ".php";
    if ( file_exists($class_path) ){
      require_once($class_path);
      return;
    }
  }
  throw new Exception("Class $class_name doesn't exist");
});
