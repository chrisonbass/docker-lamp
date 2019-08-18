<?php
require_once(__DIR__ . '/app/autoload.php');

use app\Test;
use app\db\Connect;
use app\db\Query;
use app\db\Insert;
use app\util\Setup;

if ( !extension_loaded('imagick') ) {
  echo 'imagick not installed';
}

try {
  Setup::createDatabase();
  $insert = new Insert();
  $rowId = null;
  /*
  $insert->into("app_settings")
    ->values([
      "key" => "owner",
      "value" => "Jim Watson",
      "datetimecreated" => date("Y-m-d H:i:s",time())
    ])
    ->execute();
   */
  $query = new Query();
  $str = $query->select("*")
        ->from("app_settings")
        ->all();

  if ( !file_exists(__DIR__ . "/uploads") ){
    mkdir(__DIR__ . "/uploads");
  }
  file_put_contents(__DIR__ . "/uploads/test.txt", "This is a test");
  echo "<pre>";
  print_r("New Row Id: " . $rowId . "\n");
  print_r($str . "\n");
  print_r(file_get_contents(__DIR__ . "/uploads/test.txt") . "\n" );
  echo "</pre>";
  exit;
} catch ( Exception $e ){
  echo "<pre>";
  print_r($e);
  echo "</pre>";
  exit;
}
?>
