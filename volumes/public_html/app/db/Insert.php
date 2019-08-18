<?php

namespace app\db;

class Insert {
  protected $into;
  protected $keyValues = [];
  protected $options = [];

  public function into($table_name){
    $this->into = $table_name;
    return $this;
  }

  public function values($key_value_array){
    $this->keyValues = $key_value_array;
    return $this;
  }

  public function execute(){
    $statement_str = (string)$this;
    $conn = Connect::factory()->getConnection();
    $stmt = $conn->prepare($statement_str);
    foreach( $this->options as $name => $value ){
      $stmt->bindValue($name, $value);
    }
    $res = $stmt->execute();
    if ( $res ){
      $res = $conn->lastInsertId();
    }
    return $res;
  }

  public function __toString(){
    $insert_str = "INSERT INTO `{$this->into}`";
    $keys = array_keys($this->keyValues); 
    $values = array_values($this->keyValues);
    $prep_value_names = array_map(function($entry){
      return ":" . uniqid() . "__" . uniqid();
    }, $values); 
    $insert_str .= " ( `".implode("`, `", $keys)."` ) VALUES ( " . implode(", ", $prep_value_names) . " ) ";
    for( $i = 0; $i < count($prep_value_names); $i++ ){
      $this->options[$prep_value_names[$i]] = $values[$i];
    }
    return $insert_str;
  }
}
