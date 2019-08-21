<?php
require_once(__DIR__ . '/app/autoload.php');

// phpinfo(); exit;

use app\Test;
use app\db\Connect;
use app\db\Query;
use app\db\Insert;
use app\util\Setup;
use \Imagick;


if ( !extension_loaded('imagick') ) {
  echo 'imagick not installed';
}

try {
  Setup::createDatabase();
  $insert = new Insert();
  $rowId = null;
  /*
  $rowId = $insert->into("app_settings")
    ->values([
      "key" => "owner",
      "value" => "Jim Watson",
      "datetimecreated" => date("Y-m-d H:i:s",time())
    ])
    ->execute();
   */
  $query = new Query();
  $results = $query->select("*")
        ->from("app_settings")
        ->all();

  echo "<pre>";
  if ( $rowId ){
    print_r("New Row Id: " . $rowId . "\n");
  }
  if ( $results && count($results) ){
    print_r("Query Results:\n");
    print_r($results);
  }
  print_r(file_get_contents("/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini") . "\n");
  print_r("Timezone: " . date_default_timezone_get() . "\n");
  echo "</pre>";
  exit;
} catch ( Exception $e ){
  echo "<pre>";
  print_r($e);
  echo "</pre>";
  exit;
}
?>
