<?php
require_once(__DIR__ . '/app/autoload.php');

use app\Test;
use app\Files;

try {
  $t = new Test();
  $f = new Files();
  echo "<pre>";
  print_r($t);
  echo "</pre>";
  exit;
} catch ( Exception $e ){
  echo "<pre>";
  print_r($e);
  echo "</pre>";
  exit;
}
?>
