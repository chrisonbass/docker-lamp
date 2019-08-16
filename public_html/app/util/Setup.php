<?php

namespace app\util;

use app\db\Connect;

class Setup {
  public static function createDatabase(){
    $create_table_str = "
      CREATE TABLE IF NOT EXISTS `app_settings` (
        `id`  bigint(22) UNSIGNED NOT NULL AUTO_INCREMENT ,
        `key`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL ,
        `value`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL ,
        `datetimecreated`  datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP ,
        `datetimestamp`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
        PRIMARY KEY (`id`)
      )
      ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
    ";
    $conn = Connect::factory()->getConnection();
    $conn->exec($create_table_str);
  }
}
