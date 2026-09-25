<?php
declare(strict_types=1);

/**
 * Database access layer.
 *
 * @package   AMXBans
 * @author    AMXBans PHP 8 contributors
 * @license   CC-BY-NC-SA-2.0
 * @see       docs/architecture.md#database
 */

/**
 * Single point of access to the AMXBans database.
 *
 * Every file in the project talks to MySQL/MariaDB through this class
 * (or through the {@see getPDO()} shortcut), so the connection settings -
 * charset, error mode, fetch mode, native prepared statements - are set in
 * exactly one place. The connection is opened lazily on first use.
 *
 * Table names are always passed WITHOUT the prefix; {@see Database::table()}
 * adds the configured prefix and quotes the name. Column names given as array
 * keys to insert()/update()/delete() are validated against [A-Za-z0-9_].
 * Values are always bound as parameters - never concatenate user input into SQL.
 *
 * ```php
 * $bans = Database::all(
 *     'SELECT * FROM ' . Database::table('bans') . ' WHERE `expired` = :e LIMIT :l',
 *     ['e' => 0, 'l' => 50]
 * );
 * $id = Database::insert('logs', ['username' => 'admin', 'action' => 'Test']);
 * Database::update('bans', ['expired' => 1], ['bid' => 5]);
 * Database::delete('comments', ['bid' => 5]);
 * ```
 *
 * Note: with native prepared statements a named placeholder may appear only
 * once per query - use `:ip1`, `:ip2` when the same value is needed twice.
 */
final class Database
{
    /** @var PDO|null Shared connection, created by {@see Database::pdo()}. */
    private static ?PDO $pdo = null;

    /** @var stdClass|null Connection settings (db_host, db_user, db_pass, db_db, db_prefix). */
    private static ?stdClass $config = null;

    /**
     * Supplies connection settings and drops an already open connection.
     *
     * @param stdClass $config Object with the properties `db_host`, `db_user`, `db_pass`,
     *                         `db_db` and `db_prefix` (format of include/db.config.inc.php).
     * @return void
     */
    public static function configure(stdClass $config): void
    {
        self::$config = $config;
        self::$pdo = null;
    }

    /**
     * Returns the shared PDO connection, opening it on first call.
     *
     * Falls back to the global `$config` object when {@see Database::configure()}
     * was not called (compatibility with AMXBans 6 modules).
     *
     * @return PDO Connection with ERRMODE_EXCEPTION, FETCH_ASSOC and native prepares.
     * @throws RuntimeException When no configuration is available.
     * @throws PDOException     When the connection fails.
     */
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

    /**
     * Returns the configured table prefix (without the trailing underscore).
     *
     * @return string Prefix such as "amx"; validated, safe to embed in SQL.
     * @throws RuntimeException When the prefix contains characters other than [A-Za-z0-9_].
     */
    public static function prefix(): string
    {
        $prefix = (string)(self::$config->db_prefix ?? $GLOBALS['config']->db_prefix ?? 'amx');
        if (!preg_match('/^[A-Za-z0-9_]+$/', $prefix)) {
            throw new RuntimeException('Invalid table prefix.');
        }
        return $prefix;
    }

    /**
     * Builds a quoted, prefixed table name.
     *
     * @param string $name Table name without prefix, e.g. "bans".
     * @return string Quoted name, e.g. "`amx_bans`".
     * @throws InvalidArgumentException When $name contains characters other than [A-Za-z0-9_].
     */
    public static function table(string $name): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException('Invalid table name.');
        }
        return '`' . self::prefix() . '_' . $name . '`';
    }

    /**
     * Prepares and executes a statement.
     *
     * Integers are bound as PARAM_INT (required for LIMIT/OFFSET), booleans as
     * PARAM_BOOL, null as PARAM_NULL and everything else as a string.
     *
     * @param string                        $sql    SQL with named (`:name`) or positional (`?`) placeholders.
     * @param array<int|string, mixed>      $params Values; keys with or without the leading colon.
     * @return PDOStatement The executed statement.
     * @throws PDOException On SQL errors.
     */
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

    /**
     * Fetches the first row of a query.
     *
     * @param string               $sql    SQL query.
     * @param array<string, mixed> $params Bound parameters.
     * @return array<string, mixed>|null Associative row or null when there is no result.
     * @throws PDOException On SQL errors.
     */
    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /**
     * Fetches all rows of a query.
     *
     * @param string               $sql    SQL query.
     * @param array<string, mixed> $params Bound parameters.
     * @return list<array<string, mixed>> Associative rows (empty array when nothing matched).
     * @throws PDOException On SQL errors.
     */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /**
     * Fetches the first column of every row.
     *
     * @param string               $sql    SQL query.
     * @param array<string, mixed> $params Bound parameters.
     * @return list<mixed> Values of the first column.
     * @throws PDOException On SQL errors.
     */
    public static function column(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Fetches a single value (first column of the first row), e.g. for COUNT(*).
     *
     * @param string               $sql    SQL query.
     * @param array<string, mixed> $params Bound parameters.
     * @return mixed The value or null when the query returned no rows.
     * @throws PDOException On SQL errors.
     */
    public static function value(string $sql, array $params = []): mixed
    {
        $value = self::run($sql, $params)->fetchColumn();
        return $value === false ? null : $value;
    }

    /**
     * Inserts one row.
     *
     * @param string               $table Table name without prefix.
     * @param array<string, mixed> $data  Column => value.
     * @return int The new AUTO_INCREMENT id (0 for tables without one).
     * @throws InvalidArgumentException On invalid table/column names.
     * @throws PDOException On SQL errors.
     */
    public static function insert(string $table, array $data): int
    {
        $columns = array_map(self::columnName(...), array_keys($data));
        $placeholders = array_map(fn($k) => ':' . $k, array_keys($data));
        self::run(
            'INSERT INTO ' . self::table($table) . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')',
            $data
        );
        return (int)self::pdo()->lastInsertId();
    }

    /**
     * Updates rows matching all conditions of $where (joined with AND).
     *
     * @param string               $table Table name without prefix.
     * @param array<string, mixed> $data  Column => new value.
     * @param array<string, mixed> $where Column => required value; must not be empty.
     * @return int Number of affected rows.
     * @throws InvalidArgumentException When $where is empty or names are invalid.
     * @throws PDOException On SQL errors.
     */
    public static function update(string $table, array $data, array $where): int
    {
        if (!$where) {
            throw new InvalidArgumentException('Refusing to UPDATE without a WHERE clause.');
        }
        $set = [];
        $params = [];
        foreach ($data as $col => $value) {
            $set[] = self::columnName($col) . ' = :s_' . $col;
            $params['s_' . $col] = $value;
        }
        [$cond, $whereParams] = self::where($where);
        return self::run(
            'UPDATE ' . self::table($table) . ' SET ' . implode(', ', $set) . ' WHERE ' . $cond,
            $params + $whereParams
        )->rowCount();
    }

    /**
     * Deletes rows matching all conditions of $where (joined with AND).
     *
     * @param string               $table Table name without prefix.
     * @param array<string, mixed> $where Column => value; must not be empty.
     * @return int Number of deleted rows.
     * @throws InvalidArgumentException When $where is empty or names are invalid.
     * @throws PDOException On SQL errors.
     */
    public static function delete(string $table, array $where): int
    {
        if (!$where) {
            throw new InvalidArgumentException('Refusing to DELETE without a WHERE clause.');
        }
        [$cond, $params] = self::where($where);
        return self::run('DELETE FROM ' . self::table($table) . ' WHERE ' . $cond, $params)->rowCount();
    }

    /**
     * Runs a callback inside a transaction.
     *
     * The transaction is committed when the callback returns and rolled back
     * when it throws (the exception is re-thrown).
     *
     * @template T
     * @param callable(PDO): T $fn Work to do; receives the PDO connection.
     * @return T Whatever the callback returned.
     * @throws Throwable Anything thrown by the callback.
     */
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

    /**
     * Builds "`a` = :w_a AND `b` = :w_b" and the matching parameters.
     *
     * @param array<string, mixed> $where Column => value.
     * @return array{0: string, 1: array<string, mixed>} SQL fragment and parameters.
     */
    private static function where(array $where): array
    {
        $cond = [];
        $params = [];
        foreach ($where as $col => $value) {
            $cond[] = self::columnName($col) . ' = :w_' . $col;
            $params['w_' . $col] = $value;
        }
        return [implode(' AND ', $cond), $params];
    }

    /**
     * Validates and quotes a column name.
     *
     * @param string $name Column name.
     * @return string Quoted column name.
     * @throws InvalidArgumentException When the name contains characters other than [A-Za-z0-9_].
     */
    private static function columnName(string $name): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException('Invalid column name.');
        }
        return '`' . $name . '`';
    }

    /**
     * Splits "host", "host:port" or "[ipv6]:port" into host and port.
     *
     * @param string $host Value of db_host.
     * @return array{0: string, 1: int|null} Host and optional port.
     */
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

/**
 * Shortcut for {@see Database::pdo()}, kept for AMXBans 6 modules.
 *
 * @return PDO The shared connection.
 * @throws RuntimeException|PDOException See {@see Database::pdo()}.
 */
function getPDO(): PDO
{
    return Database::pdo();
}
