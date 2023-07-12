<?php

namespace AdeptCMS\Application;

use \PDO;

defined('_ADEPT_INIT') or die();

class Database
{
  /**
   * The database connection object
   *
   * @var mysqli $db
   */
  protected $connection;

  /**
   * Log queries and results
   *
   * @param bool
   */
  protected $log;

  /**
   * Construct
   * 
   * @return null
   */
  public function __construct(\AdeptCMS\Base\Configuration &$configuration)
  {
    $conf = $configuration->database;

    switch ($conf->type) {
      case 'cockroach':
        $dsn  = 'pgsql:';
        $dsn .= 'host= ' . $conf->host . ';';
        $dsn .= 'port=' . $conf->port . ';';
        $dsn .= 'dbname=' . $conf->database . ';';
        $dsn .= 'user=' . $conf->username . ';';
        $dsn .= 'sslmode=require;';
        $dsn .= 'sslkey=' . $conf->key . ';';
        $dsn .= 'sslcert=.' . $conf->cert . ';';
        break;

      case 'mysql':
        $dsn  = 'mysql:';
        $dsn .= 'host=' . $conf->host . ';';
        $dsn .= 'dbname=' . $conf->database . ';';
        break;
    }

    try {
      $this->connection = new PDO(
        $dsn,                                                 // DSN
        $conf->username,                                      // Username
        ($conf->type == 'mysql') ? $conf->password : null,    // Password
        array(                                                // Options
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES => true,
          PDO::ATTR_PERSISTENT => true
        )
      );
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'Database Connection Error',
        $e->getMessage(),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }
  }

  /**
   * Insert into a database table
   * 
   * @param string $query The query to be executed
   * @param array $params An array of key->values to be used with the query
   * 
   * @return bool
   **/
  public function insert(string $query, array $params): bool
  {
    $result = false;

    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $result = $stmt->execute($params);
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  /**
   * Insert into a database table
   * 
   * @param string $query The query to be executed
   * @param array $params An array of key->values to be used with the query
   * 
   * @return bool
   **/
  public function insertGetId(string $query, array $params): int
  {
    $result = 0;

    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);

      for ($i = 0; $i < count($params); $i++) {
        if (is_bool($params[$i])) {
          $params[$i] = ($params[$i]) ? 1 : 0;
        }
      }

      if ($stmt->execute($params) !== false) {
        $result = (int)$this->connection->lastInsertId();
      }
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  /**
   * Insert into a database and get the id generated
   * 
   * @param string $table The database table to insert into
   * @param array $data An array of key->values to be used with the query
   * 
   * @return int
   **/
  public function insertSingleTableGetId(string $table, array $data): int
  {
    $id = 0;

    try {
      $params = [];
      $set = '';
      $i = count($data);

      foreach ($data as $k => $v) {
        $set .= "`$k` = ?";

        if (is_bool($v)) {
          $params[] = ($v) ? 1 : 0;
        } else {
          $params[] = $v;
        }

        if ($i > 1) {
          $set .= ', ';
          $i = $i - 1;
        }
      }

      $params = array_merge($params, $params);

      $query  = "INSERT INTO `$table`";
      $query .= " SET $set";
      $query .= " ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), $set";

      if ($this->insert($query, $params)) {
        $id = (int)$this->connection->lastInsertId();
      }
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $id;
  }

  public function update(string $query, array $params): bool
  {
    $result = false;

    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $result = $stmt->execute($params);
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  public function getString(string $query, array $params): string|bool
  {
    $result = $this->getValue($query, $params);

    if ($result === null) {
      $result = '';
    }

    return ($result === false) ? false : (string)$result;
  }

  public function getInt(string $query, array $params): int
  {
    $result = $this->getValue($query, $params);

    if ($result === null) {
      $int = 0;
    }

    if ($result === false) {
      $result = 0;
    }

    return (int)$result;
  }

  public function getValue(string $query, array $params): string|int|bool|null
  {
    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $stmt->execute($params);
      $result = $stmt->fetchColumn();
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  /**
   * Undocumented function
   *
   * @param string $query
   * @param array $params
   * @return array|bool
   */
  public function getValues(string $query, array $params = []): array|bool
  {
    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $stmt->execute($params);
      $result = $stmt->fetchAll();
    } catch (\PDOException $e) {

      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  public function getObject(string $query, array $params = []): object|bool
  {
    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $stmt->execute($params);
      $result = $stmt->fetchObject();
    } catch (\PDOException $e) {
      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  /**
   * Undocumented function
   *
   * @param string $query
   * @param array $params
   * @return array|bool
   */
  public function getObjects(string $query, array $params = []): array|bool
  {
    try {
      $db = $this->connection;

      $stmt = $db->prepare($query);
      $stmt->execute($params);
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (\PDOException $e) {

      throw new \AdeptCMS\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $result;
  }

  public function getLastId(): int
  {
    return $this->db->lastInsertId();
  }

  protected function formatErrorMessage(\PDOException $e, string $query, array $params): string
  {
    $out = '';

    if (!empty($e->getMessage())) {
      $out .= '<p>' . $e->getMessage() . '</p>';
    }

    $out .= '<h3>Query</h3><p>' . $query . '</p>';
    $out .= '<h3>Params</h3><pre>' . print_r($params, true) . '</pre>';

    return $out;
  }
}
