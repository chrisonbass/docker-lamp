<?php

namespace app\db;

use \PDO;

class Connect {
  private static $static_connection;
  protected $host = "devmysql";
  protected $userName = "root";
  protected $password = "test1234";
  protected $connection;
  protected $error;

  public function __construct($host = null, $user = null, $password = null){
    if ( !is_null($host) ){
      $this->host = $host;
    }
    if ( !is_null($user) ){
      $this->userName = $user;
    }
    if ( !is_null($password) ){
      $this->password = $password;
    }
  }

  public static function factory($host = null, $user = null, $password = null){
    if ( !static::$static_connection ){
      static::$static_connection = new Connect($host, $user, $password);
    }
    return static::$static_connection;
  }

  public function getConnection(){
    unset($this->error);
    if ( !$this->connection ){
      try {
        $dsn = "mysql:dbname=test_db;host={$this->host};";
        $options = [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->connection = new PDO($dsn, $this->userName, $this->password, $options);
      } catch ( PDOException $e ){
        $this->error = "Connection error: {$e->getMessage()}";
      }
    }
    return $this->connection;
  }

  public function getError(){
    return $this->error;
  }

  public function hasError(){
    return is_string($this->error);
  }
}
