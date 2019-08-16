<?php

namespace app\db;

class Query {
  protected $selects = [];
  protected $from;
  protected $joins = [];
  protected $conditions = [];
  protected $orderBy;
  protected $groupBy;
  protected $limit;
  protected $offset;
  protected $options = [];

  private function processOptions($options_to_merge = []){
    $this->options = array_merge([], $this->options, $options_to_merge);
  }

  public function select($mixed_select){
    if ( is_string($mixed_select) ){
      $mixed_select = preg_split("/,/", $mixed_select);
    } 
    if ( is_array($mixed_select) ){
      $this->selects = array_map(function($select){
        $split = array_filter(preg_split("/\./", $select),function($entry){
          return strlen(trim("" . $entry)) > 0;
        });
        if ( count($split) == 2 ){
          return "`{$split[0]}`." .  ( $split[1] == "*" ? $split[1] : "`{$split[1]}`" );
        } else if ( count($split) == 1 ){
          return "`{$split[0]}`";
        }
      }, array_filter($mixed_select, function($select){
        return strlen(trim("" . $select)) > 0;
      }));
    }
    return $this;
  }

  public function from($table_name_string){
    $this->from = $table_name_string; 
    return $this;
  }

  public function join($join_type, $table_name, $join_on, $options = []){
    $this->joins[] = [
      $join_type,
      $table_name,
      $join_on
    ];
    $this->processOptions($options);
    return $this;
  }

  public function orderBy($order_by_string, $options = []){
    $this->orderBy = $order_by_string;
    $this->processOptions($options);
    return $this;
  }

  public function groupBy($group_by_string){
    $this->groupBy = $group_by_string;
  }

  public function limit($max_num_of_records){
    $this->limit = $max_num_of_records;
    return $this;
  }

  public function offset($start_at_record_index){
    $this->offset = $start_at_record_index;
    return $this;
  }

  public function where($mixed_where, $options = []){
    if ( is_array($mixed_where) ){
      $is_hashed = false;
      $is_operand = false;
      $keys = array_keys($mixed_where);
      if ( is_numeric($keys[0]) ){
        $is_operand = true;
      } else {
        $is_hash = true;
      }
      if ( $is_hash ){
        foreach ( $mixed_where as $key => $value ){
          $prep_value_name = "{$key}_" . uniqid() . "_" . uniqid();
          $this->conditions[] = ( count($this->conditions) > 0 ? "AND " : "" ) . 
            "( `{$key}` = :$prep_value_name )";
          $options = array_merge([], $options, [
            $prep_value_name => $value
          ] );
        }
      } 
    }
    $this->processOptions($options);
    return $this;
  }

  private function prepStatement(){
    $query_str = (string)$this;
    $conn = Connect::factory()->getConnection();
    $stmt = $conn->prepare($query_str);
    foreach ( $this->options as $name => $value ){
      $stmt->bindValue(":{$name}", $value);
    }
    return $stmt;
  }

  public function all(){
    $stmt = $this->prepStatement();
    $rows = [];
    $stmt->execute();
    while ( $row = $stmt->fetch() ){
      $rows[] = $row;
    }
    return $rows;
  }

  public function one(){
    $stmt = $this->prepStatement();
    $stmt->execute();
    return $stmt->fetch();
  }

  public function __toString(){
    $query_str = "SELECT";
    $query_str .= " " . implode(",", $this->selects);
    $query_str .= " FROM {$this->from}";
    foreach( $this->joins as $join ){
      $query_str .= " " . preg_replace("/join/","", strtolower($join[0])) . " JOIN" .
        " {$join[1]} ON {$join[2]} ";
    }
    if ( count($this->conditions) ){
      $query_str .= " WHERE " . implode(" ", $this->conditions);
    }
    if ( is_string($this->groupBy) && strlen(trim($this->groupBy)) ){
      $query_str .= " GROUP BY {$this->groupBy}";
    }
    if ( is_string($this->orderBy) && strlen(trim($this->orderBy)) ){
      $query_str .= " ORDER BY {$this->orderBy}";
    }
    if ( is_string($this->limit) || is_numeric($this->limit) ){
      $query_str .= " LIMIT {$this->limit}";
    }
    if ( is_string($this->offset) || is_numeric($this->offset) ){
      $query_str .= " OFFSET {$this->offset}";
    }
    return $query_str;
  }
}
