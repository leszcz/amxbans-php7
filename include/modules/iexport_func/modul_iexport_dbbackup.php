<?php
declare(strict_types=1);

/**
 * Creates an SQL dump of the AMXBans tables (only tables with the configured prefix).
 * Returns the SQL as a string.
 */
function db_backup(bool $structureOnly, bool $dropTable, bool $bansOnly): string
{
    $pdo = Database::pdo();
    $prefix = Database::prefix();
    $tables = $bansOnly
        ? [$prefix . '_bans']
        : Database::column('SHOW TABLES LIKE ' . $pdo->quote(addcslashes($prefix, '_%') . '\_%'));

    $out = "-- AMXBans backup " . date('Y-m-d H:i:s') . "\n-- Tables: " . implode(', ', $tables) . "\n\n"
        . "SET NAMES utf8mb4;\nSET foreign_key_checks = 0;\n\n";
    foreach ($tables as $table) {
        $q = '`' . str_replace('`', '``', $table) . '`';
        if ($dropTable) {
            $out .= "DROP TABLE IF EXISTS $q;\n";
        }
        $create = Database::one("SHOW CREATE TABLE $q");
        $out .= ($create['Create Table'] ?? '') . ";\n\n";
        if ($structureOnly) {
            continue;
        }
        $stmt = $pdo->query("SELECT * FROM $q");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $values = array_map(fn($v) => $v === null ? 'NULL' : (is_int($v) || is_float($v) ? (string)$v : $pdo->quote((string)$v)), array_values($row));
            $out .= "INSERT INTO $q (`" . implode('`, `', array_keys($row)) . '`) VALUES (' . implode(', ', $values) . ");\n";
        }
        $out .= "\n";
    }
    return $out . "SET foreign_key_checks = 1;\n";
}
