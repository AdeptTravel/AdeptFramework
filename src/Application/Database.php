<?php

namespace Adept\Application;

use \PDO;
use \Adept\Application;
use \Adept\Abstract\Configuration\Database as Configuration;

defined('_ADEPT_INIT') or die();


class Database
{
  /**
   * The database connection object
   *
   * @var  \PDO $connection
   */
  protected \PDO $connection;


  /**
   * Undocumented function
   *
   * @param  \Adept\Abstract\Configuration $configuration
   */
  public function __construct(Configuration &$conf)
  {
    $dsn  = 'mysql:';
    $dsn .= 'host=' . $conf->host . ';';
    $dsn .= 'dbname=' . $conf->database . ';';

    try {
      $this->connection = new PDO(
        $dsn,                                                 // DSN
        $conf->username,                                      // Username
        $conf->password,    // Password
        array(                                                // Options
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES => true,
          PDO::ATTR_PERSISTENT => true
        )
      );
    } catch (\PDOException $e) {
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
   * Insert into a database table
   * 
   * @param string $query The query to be executed
   * @param array $params An array of key->values to be used with the query
   * 
   * @return bool
   **/
  public function insert(string $query, array $params = []): bool
  {
    for ($i = 0; $i < count($params); $i++) {
      $params[$i] = trim($params[$i]);
    }

    return ($this->execute($query, $params) !== false);
  }

  /**
   * Insert into a database table
   * 
   * @param string $query The query to be executed
   * @param array $params An array of key->values to be used with the query
   * 
   * @return bool
   **/
  public function insertGetId(string $query, array $params = []): int
  {
    $result = 0;

    for ($i = 0; $i < count($params); $i++) {

      $params[$i] = trim($params[$i]);

      if (is_bool($params[$i])) {
        $params[$i] = ($params[$i]) ? 1 : 0;
      }
    }

    if ($this->execute($query, $params) !== false) {
      $result = (int)$this->connection->lastInsertId();
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
  public function insertSingleTableGetId(string $table, object $data): int
  {
    $id = 0;

    $params = [];
    $key = '';
    $val = '';

    foreach ($data as $k => $v) {
      $key .= "`$k`, ";
      $val .= '?, ';

      if (is_bool($v)) {
        $params[] = ($v) ? 1 : 0;
      } else {
        $params[] = trim($v);
      }
    }

    $key = substr($key, 0, -2);
    $val = substr($val, 0, -2);

    $query  = "INSERT INTO `$table` ($key) VALUES ($val)";

    if ($this->insert($query, $params)) {
      $id = (int)$this->connection->lastInsertId();
    }

    return $id;
  }

  public function update(string $query, array $params): bool
  {
    return ($this->execute($query, $params) !== false);
  }

  /**
   * Update into a database
   * 
   * @param string $table The database table to insert into
   * @param object $data An array of key->values to be used with the query
   * 
   * @return bool
   **/
  public function updateSingleTable(string $table, object $data): bool
  {
    $params = [];
    $set    = '';
    $i      = count($data) - 1;

    foreach ($data as $k => $v) {

      if ($k == 'id') {
        continue;
      }

      $set .= "`$k` = ?";

      if (is_bool($v)) {
        $params[] = ($v) ? 1 : 0;
      } else {
        $params[] = trim($v);
      }

      if ($i > 1) {
        $set .= ', ';
        $i = $i - 1;
      }
    }

    $params[] = $data['id'];

    $query  = "UPDATE `$table`";
    $query .= " SET $set";
    $query .= " WHERE `id` = ?";

    return $this->update($query, $params);
  }

  public function getColumns($table): array|bool
  {
    //$stmt = $this->execute("DESCRIBE `$table`");
    //return $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $this->connection->prepare("DESCRIBE `$table`");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $result;
  }

  public function getString(string $query, array $params = []): string|bool
  {
    $result = $this->getValue($query, $params);

    if ($result === null) {
      $result = '';
    }

    return ($result === false) ? false : (string)$result;
  }

  public function getInt(string $query, array $params = []): int
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

  public function getValue(string $query, array $params = []): string|int|bool|null
  {
    $stmt = $this->execute($query, $params);
    return $stmt->fetchColumn();
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
    $stmt = $this->execute($query, $params);
    return $result = $stmt->fetchAll();
  }

  public function getObject(string $query, array $params = []): object|bool
  {
    $stmt = $this->execute($query, $params);
    $result = $stmt->fetchObject();
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
    $stmt = $this->execute($query, $params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Delete a record
   *
   * @param  string     $table
   * @param  int|string $id
   * @param  string     $col
   *
   * @return void
   */
  public function delete(string $table, int|string $id, string $col = 'id')
  {
    return $this->update(
      "DELETE FROM `$table` WHERE `$col` = ?",
      [$id]
    );
  }

  public function getLastId(): int
  {
    return $this->connection->lastInsertId();
  }

  public function isDuplicate(string $table, object $data): bool
  {
    $query = "SELECT count(*) FROM `$table` WHERE ";
    $params = [];


    foreach ($data as $k => $v) {
      $query .= "`$k` = ? AND ";
      $params[] = $v;
    }

    // Remove AND form end of Query after the foreach function
    $query = substr($query, 0, strlen($query) - 4);

    return ($this->getInt($query, $params) > 0);
  }

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

  public function getQueryDebug(string $query, array $params)
  {
    $query = preg_replace_callback('/\?/', function ($match) use (&$params) {
      return array_shift($params) . ' ' . "\n";
    }, $query);

    return $query;
  }

  protected function execute(string $query, array $params = []): \PDOStatement|bool
  {
    if (Application::getInstance()->conf->log->query) {
      Application::getInstance()->log->logQuery($query, $params, $this->getQueryDebug($query, $params));
    }

    try {
      $stmt = $this->connection->prepare($query);

      if ($stmt->execute($params)) {
        return $stmt;
      } else {
        return false;
      }
    } catch (\PDOException $e) {
      throw new \Adept\Exceptions\Database\PDOException(
        'PDO Exception',
        $this->formatErrorMessage($e, $query, $params),
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }
  }
}
