<?php
namespace app\utils;

use app\core\Database;

class DbUtils {
    private static ?Database $db = null;

    private static function getDatabaseInstance(): Database {
        if (self::$db === null) {
            self::$db = new Database();
        }
        return self::$db;
    }

    /**
     * Get the columns of a specific table.
     * @param string $tableName
     * @return array
     */
    public static function getTableColumns(string $tableName): array {
        $db = self::getDatabaseInstance();
        $db->query("SHOW COLUMNS FROM $tableName");
        return $db->resultSet();
    }
}
