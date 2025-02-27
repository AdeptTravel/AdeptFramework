<?php

declare(strict_types=1);

namespace Adept\Application;


use PDO;
use Adept\Application;
use Adept\Application\Configuration;
use Adept\Application\Database\Query;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die();

/**
 * \Adept\Application\Database
 *
 * Handles database interactions using PDO
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz
 * @copyright  2021-2024 The Adept Traveler, Inc.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.1.0
 */
class Database
{
  /**
   * The PDO database connection object.
   *
   * @var PDO
   */
  protected PDO $connection;

  /**
   * Constructor.
   *
   * Initializes the database connection using the provided configuration.
   *
   * @param Configuration $conf Database configuration object
   * @throws \Adept\Exceptions\Database\PDOException If the connection fails
   */
  public function __construct(Configuration $conf)
  {
    // Build the DSN (Data Source Name). Extendable to include port, charset, etc.
    $dsn  = 'mysql:';
    $dsn .= 'host=' . $conf->getString('Database.Host') . ';';
    $dsn .= 'dbname=' . $conf->getString('database.database') . ';';

    try {
      // Create a new PDO connection with specified options.
      $this->connection = new PDO(
        $dsn,                        // DSN
        $conf->getString('database.username'),  // Username
        $conf->getString('database.password '), // Password
        [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          // Using native prepared statements is preferable if supported
          PDO::ATTR_EMULATE_PREPARES   => true,
          PDO::ATTR_PERSISTENT         => true
        ]
      );
    } catch (\PDOException $e) {
      // Throw a custom exception if the connection fails.
      throw new \Adept\Exceptions\Database\PDOException(
        'Database Connection Error',
        $e->getMessage(),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }
  }

  /**
   * Process a single parameter.
   *
   * Trims strings, converts booleans to integers, and leaves null values unchanged.
   *
   * @param mixed $value The parameter to process.
   * @return mixed The processed parameter.
   */
  private function processParam($value)
  {
    if (is_bool($value)) {
      return $value ? 1 : 0;
    } else if (is_null($value)) {
      return null;
    }

    return is_string($value) ? trim($value) : $value;
  }

  /**
   * Process an array of parameters.
   *
   * @param array $params The parameters to process.
   * @return array The processed parameters.
   */
  private function processParams(array $params): array
  {
    return array_map([$this, 'processParam'], $params);
  }

  /**
   * Generate a debug version of a parameter.
   *
   * Wraps strings in quotes, returns numeric values as-is, and shows 'NULL' for null values.
   *
   * @param mixed $param The parameter to format.
   * @return string The formatted parameter.
   */
  private function debugParam($param): string
  {
    if (is_null($param)) {
      return 'NULL';
    } elseif (is_numeric($param)) {
      return (string)$param;
    } else {
      return "'" . addslashes((string)$param) . "'";
    }
  }

  /**
   * Get a debug version of the query with parameters replaced.
   *
   * @param string $query  The SQL query with placeholders.
   * @param array  $params The parameters to replace in the query.
   * @return string The debug version of the query.
   */
  public function getQueryDebug(string $query, array $params): string
  {
    // Create a copy of parameters to avoid modifying the original array.
    $paramsCopy = $params;
    // Replace each placeholder with the corresponding processed parameter.
    $query = preg_replace_callback('/\?/', function ($match) use (&$paramsCopy) {
      return $this->debugParam(array_shift($paramsCopy));
    }, $query);

    return $query;
  }

  /**
   * Execute a prepared statement with parameters.
   *
   * Logs the query if logging is enabled, prepares the SQL statement,
   * and executes it with the provided parameters.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params The parameters bound to the query.
   * @return \PDOStatement|bool Returns the PDOStatement on success, false on failure.
   * @throws \Adept\Exceptions\Database\PDOException If query execution fails.
   */
  protected function execute(string $query, array $params = []): \PDOStatement|bool
  {
    // Log the query if logging is enabled.
    if (Application::getInstance()->conf->log->query) {
      Application::getInstance()->log->logQuery(
        $query,
        $params,
        $this->getQueryDebug($query, $params)
      );
    }

    try {
      // Prepare the SQL statement.
      $stmt = $this->connection->prepare($query);

      // Execute the statement with processed parameters.
      if ($stmt->execute($this->processParams($params))) {
        return $stmt;
      } else {
        return false;
      }
    } catch (\PDOException $e) {
      // Throw a custom exception if the execution fails.
      throw new \Adept\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }
  }

  public function query(Query $builder): array
  {
    $sql = $builder->getQuery();
    $params = $builder->getParams();
    return $this->fetchAll($sql, $params);
  }

  /**
   * Insert data into a database table.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return bool Returns true on success, false on failure.
   */
  public function insert(string $query, array $params = []): bool
  {
    return ($this->execute($query, $params) !== false);
  }

  /**
   * Insert data into a database table and get the last inserted ID.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return int Returns the last inserted ID.
   */
  public function insertGetId(string $query, array $params = []): int
  {
    if ($this->execute($query, $params) !== false) {
      return (int)$this->connection->lastInsertId();
    }

    return 0;
  }

  /**
   * Insert data into a single table and get the last inserted ID.
   *
   * Builds the query dynamically from an object containing key-value pairs.
   *
   * @param string $table The database table to insert into.
   * @param object $data  An object with columns as keys and corresponding values.
   * @return int Returns the last inserted ID.
   */
  public function insertSingleTableGetId(string $table, object $data): int
  {
    $params = [];
    $keys = [];
    $placeholders = [];

    // Build the query keys and placeholders.
    foreach ($data as $k => $v) {
      $keys[] = "`$k`";
      $placeholders[] = '?';
      $params[] = $this->processParam($v);
    }

    // Create comma-separated lists of keys and placeholders.
    $keysStr = implode(', ', $keys);
    $placeholdersStr = implode(', ', $placeholders);

    // Construct the SQL query.
    $query = "INSERT INTO `$table` ($keysStr) VALUES ($placeholdersStr)";

    // Execute the insert and return the last inserted ID.
    if ($this->insert($query, $params)) {
      return (int)$this->connection->lastInsertId();
    }

    return 0;
  }

  /**
   * Update data in a database table.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return bool Returns true on success, false on failure.
   */
  public function update(string $query, array $params): bool
  {
    return ($this->execute($query, $params) !== false);
  }

  /**
   * Update data in a single table.
   *
   * Builds the UPDATE query dynamically from an object, skipping the 'id' field.
   *
   * @param string $table The database table to update.
   * @param object $data  An object with columns as keys and corresponding values.
   * @return bool Returns true on success, false on failure.
   */
  public function updateSingleTable(string $table, object $data): bool
  {
    $params = [];
    $set = [];

    // Build the SET part of the query.
    foreach ($data as $k => $v) {
      if ($k === 'id') {
        continue; // Skip the 'id' field for updating.
      }

      $set[] = "`$k` = ?";
      $params[] = $this->processParam($v);
    }

    // Add the 'id' for the WHERE clause.
    $params[] = $data->id;

    // Create a comma-separated SET string.
    $setStr = implode(', ', $set);

    // Construct the SQL query.
    $query = "UPDATE `$table` SET $setStr WHERE `id` = ?";

    return $this->update($query, $params);
  }

  /**
   * Get the columns of a table.
   *
   * @param string $table The database table name.
   * @return array|bool Returns an array of column names or false on failure.
   */
  public function getColumns(string $table): array|bool
  {
    $cols = [];

    $key = 'Database.Table.' . $table . '.Columns';

    if (apcu_exists($key)) {
      $cols = apcu_fetch($key);
    } else {

      $stmt = $this->connection->prepare("DESCRIBE `$table`");
      $stmt->execute();

      $cols =  $stmt->fetchAll(PDO::FETCH_COLUMN);

      apcu_store($key, $cols, Application::getInstance()->conf->database->cacheColTTL);
    }

    return $cols;
  }

  /**
   * Get a single string value from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return string|bool Returns the string value or false on failure.
   */
  public function getString(string $query, array $params = []): string|bool
  {
    $result = $this->getValue($query, $params);
    return ($result === false || $result === null) ? '' : (string)$result;
  }

  /**
   * Get a single integer value from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return int Returns the integer value.
   */
  public function getInt(string $query, array $params = []): int
  {
    $result = $this->getValue($query, $params);
    return ($result === false || $result === null) ? 0 : (int)$result;
  }

  /**
   * Get a single value from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return string|int|bool|null Returns the value or false on failure.
   */
  public function getValue(string $query, array $params = []): string|int|bool|null
  {
    $stmt = $this->execute($query, $params);
    return $stmt->fetchColumn();
  }

  /**
   * Get multiple values from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return array|bool Returns an array of values or false on failure.
   */
  public function getValues(string $query, array $params = []): array|bool
  {
    $stmt = $this->execute($query, $params);
    return $stmt->fetchAll();
  }

  /**
   * Get a single object from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return object|bool Returns the object or false on failure.
   */
  public function getObject(string $query, array $params = []): object|bool
  {
    $stmt = $this->execute($query, $params);
    return $stmt->fetchObject();
  }

  /**
   * Get multiple objects from the database.
   *
   * @param string $query  The SQL query to be executed.
   * @param array  $params An array of values to be bound to the query parameters.
   * @return array|bool Returns an array of objects or false on failure.
   */
  public function getObjects(string $query, array $params = []): array|bool
  {
    $stmt = $this->execute($query, $params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Delete a record from the database.
   *
   * @param string     $table The database table name.
   * @param int|string $id    The ID of the record to delete.
   * @param string     $col   The column name to match the ID against (default is 'id').
   * @return bool             Returns true on success, false on failure.
   */
  public function delete(string $table, int|string $id, string $col = 'id'): bool
  {
    return $this->update("DELETE FROM `$table` WHERE `$col` = ?", [$id]);
  }

  /**
   * Get the last inserted ID.
   *
   * @return int Returns the last inserted ID.
   */
  public function getLastId(): int
  {
    return (int)$this->connection->lastInsertId();
  }

  /**
   * Check if a record is a duplicate.
   *
   * Constructs a WHERE clause from the provided data and checks if any record exists.
   *
   * @param string $table The database table name.
   * @param object $data  An object containing key-value pairs to check for duplicates.
   * @return bool Returns true if a duplicate exists, false otherwise.
   */
  public function isDuplicate(string $table, object $data): bool
  {
    $params = [];
    $conditions = [];

    // Build the WHERE clause.
    foreach ($data as $k => $v) {
      $conditions[] = "`$k` = ?";
      $params[] = $this->processParam($v);
    }

    $whereClause = implode(' AND ', $conditions);
    $query = "SELECT count(*) FROM `$table` WHERE $whereClause";

    return ($this->getInt($query, $params) > 0);
  }

  /**
   * Format the error message for exceptions.
   *
   * Provides details about the error, query, parameters, and a debug version of the query.
   *
   * @param \PDOException $e      The PDO exception object.
   * @param string        $query  The SQL query that caused the exception.
   * @param array         $params The parameters bound to the query.
   * @return string The formatted error message.
   */
  protected function formatErrorMessage(\PDOException $e, string $query, array $params): string
  {
    $out = '';

    if (!empty($e->getMessage())) {
      $out .= '<p>' . $e->getMessage() . '</p>';
    }

    $out .= '<h3>Query</h3><p>' . $query . '</p>';
    $out .= '<h3>Params</h3><pre>' . print_r($params, true) . '</pre>';
    $out .= '<h3>Debug Query</h3><p>' . $this->getQueryDebug($query, $params) . '</p>';

    return $out;
  }

  /**
   * Begin a database transaction.
   *
   * @return bool True on success, false on failure.
   */
  public function beginTransaction(): bool
  {
    return $this->connection->beginTransaction();
  }

  /**
   * Commit the current transaction.
   *
   * @return bool True on success, false on failure.
   */
  public function commit(): bool
  {
    return $this->connection->commit();
  }

  /**
   * Roll back the current transaction.
   *
   * @return bool True on success, false on failure.
   */
  public function rollback(): bool
  {
    return $this->connection->rollBack();
  }
}
