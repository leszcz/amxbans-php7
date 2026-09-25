<?php
declare(strict_types=1);

/**
 * Single point of access to the AMXBans database.
 *
 * Every file in the project talks to MySQL/MariaDB through this class
 * (or through the getPDO() shortcut defined below), so the connection
 * settings - charset, error mode, fetch mode, native prepares - are set
 * in exactly one place.
 *
 *   Database::all('SELECT * FROM ' . Database::table('bans') . ' WHERE expired = :e', ['e' => 0]);
 *   Database::insert('logs', ['username' => 'admin', ...]);
 *   Database::update('bans', ['expired' => 1], ['bid' => 5]);
 *   Database::delete('comments', ['bid' => 5]);
 */
final class Database
{
    private static ?PDO $pdo = null;
    private static ?stdClass $config = null;

    /**
     * Supplies connection settings (object with db_host, db_user, db_pass,
     * db_db and db_prefix - the format used by include/db.config.inc.php).
     */
    public static function configure(stdClass $config): void
    {
        self::$config = $config;
        self::$pdo = null;
    }

    public static function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $config = self::$config ?? ($GLOBALS['config'] ?? null);
        if (!$config instanceof stdClass || !isset($config->db_host, $config->db_db)) {
            throw new RuntimeException('Database is not configured.');
        }
        self::$config = $config;

        [$host, $port] = self::splitHost((string)$config->db_host);
        $dsn = 'mysql:host=' . $host . ($port !== null ? ';port=' . $port : '')
            . ';dbname=' . $config->db_db . ';charset=utf8mb4';

        self::$pdo = new PDO($dsn, (string)$config->db_user, (string)$config->db_pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ]);
        self::$pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

        return self::$pdo;
    }

    /** Table prefix from the configuration (validated, safe to put in SQL). */
    public static function prefix(): string
    {
        $prefix = (string)(self::$config->db_prefix ?? $GLOBALS['config']->db_prefix ?? 'amx');
        if (!preg_match('/^[A-Za-z0-9_]+$/', $prefix)) {
            throw new RuntimeException('Invalid table prefix.');
        }
        return $prefix;
    }

    /** Quoted, prefixed table name: Database::table('bans') => `amx_bans` */
    public static function table(string $name): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException('Invalid table name.');
        }
        return '`' . self::prefix() . '_' . $name . '`';
    }

    /** Prepares and executes a statement. Integers are bound as integers (needed for LIMIT). */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        foreach ($params as $key => $value) {
            $name = is_int($key) ? $key + 1 : (str_starts_with($key, ':') ? $key : ':' . $key);
            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                $value === null => PDO::PARAM_NULL,
                default         => PDO::PARAM_STR,
            };
            $stmt->bindValue($name, $value, $type);
        }
        $stmt->execute();
        return $stmt;
    }

    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** First column of every row. */
    public static function column(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    }

    /** First column of the first row (or null). */
    public static function value(string $sql, array $params = []): mixed
    {
        $value = self::run($sql, $params)->fetchColumn();
        return $value === false ? null : $value;
    }

    /** INSERT INTO prefix_$table; returns the new auto-increment id. */
    public static function insert(string $table, array $data): int
    {
        $columns = array_map(self::column_name(...), array_keys($data));
        $placeholders = array_map(fn($k) => ':' . $k, array_keys($data));
        self::run(
            'INSERT INTO ' . self::table($table) . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')',
            $data
        );
        return (int)self::pdo()->lastInsertId();
    }

    /** UPDATE prefix_$table SET ... WHERE col = value AND ...; returns affected rows. */
    public static function update(string $table, array $data, array $where): int
    {
        if (!$where) {
            throw new InvalidArgumentException('Refusing to UPDATE without a WHERE clause.');
        }
        $set = [];
        $params = [];
        foreach ($data as $col => $value) {
            $set[] = self::column_name($col) . ' = :s_' . $col;
            $params['s_' . $col] = $value;
        }
        [$cond, $whereParams] = self::where($where);
        return self::run(
            'UPDATE ' . self::table($table) . ' SET ' . implode(', ', $set) . ' WHERE ' . $cond,
            $params + $whereParams
        )->rowCount();
    }

    /** DELETE FROM prefix_$table WHERE col = value AND ...; returns affected rows. */
    public static function delete(string $table, array $where): int
    {
        if (!$where) {
            throw new InvalidArgumentException('Refusing to DELETE without a WHERE clause.');
        }
        [$cond, $params] = self::where($where);
        return self::run('DELETE FROM ' . self::table($table) . ' WHERE ' . $cond, $params)->rowCount();
    }

    /** Runs $fn inside a transaction; rolls back and rethrows on any exception. */
    public static function transaction(callable $fn): mixed
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();
        try {
            $result = $fn($pdo);
            $pdo->commit();
            return $result;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    private static function where(array $where): array
    {
        $cond = [];
        $params = [];
        foreach ($where as $col => $value) {
            $cond[] = self::column_name($col) . ' = :w_' . $col;
            $params['w_' . $col] = $value;
        }
        return [implode(' AND ', $cond), $params];
    }

    private static function column_name(string $name): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException('Invalid column name.');
        }
        return '`' . $name . '`';
    }

    /** "host", "host:port" or "[ipv6]:port" */
    private static function splitHost(string $host): array
    {
        if (preg_match('/^\[(.+)\](?::(\d+))?$/', $host, $m)) {
            return [$m[1], isset($m[2]) ? (int)$m[2] : null];
        }
        if (substr_count($host, ':') === 1) {
            [$h, $p] = explode(':', $host);
            return [$h, ctype_digit($p) ? (int)$p : null];
        }
        return [$host, null];
    }
}

/** Shortcut kept for existing code and third-party modules. */
function getPDO(): PDO
{
    return Database::pdo();
}
