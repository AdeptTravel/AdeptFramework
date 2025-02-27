<?php

declare(strict_types=1);

namespace Adept\Application\Database;

use Adept\Application;

/**
 * Class Query
 *
 * A comprehensive SELECT query builder supporting:
 * - Fluent interface for SELECT, JOIN, WHERE (with nested conditions), GROUP BY, HAVING,
 *   ORDER BY, LIMIT, OFFSET, and UNION clauses.
 * - Recursive queries via Common Table Expressions (CTEs) with WITH/ WITH RECURSIVE.
 * - Execution with a Database instance, basic caching, and event hooks.
 */
class Query
{
  // Basic query parts
  protected string $table = '';
  protected array  $select = [];
  protected array  $joins = [];
  protected array  $wheres = [];    // Each: ['boolean' => 'AND'|'OR', 'clause' => string, 'params' => array]
  protected array  $groups = [];
  protected array  $havings = [];   // Similar to wheres
  protected array  $orderBy = [];
  protected ?int   $limit = null;
  protected ?int   $offset = null;
  protected array  $unions = [];    // Each: ['type' => 'UNION'|'UNION ALL', 'builder' => Query]

  // New: CTEs for recursive queries.
  // Each CTE is defined as an array with keys: name, query (a Query instance), and recursive (bool)
  protected array $ctes = [];

  // Event hooks for query building
  protected static array $beforeBuildHooks = [];
  protected static array $afterBuildHooks = [];

  // Query caching properties
  protected static array $cache = [];

  // --- Factory method ---
  public static function table(string $table): self
  {
    $instance = new self();
    $instance->table = $table;
    return $instance;
  }

  // --- SELECT columns ---
  public function select(array $columns = ['*']): self
  {
    $this->select = $columns;
    return $this;
  }

  // --- JOIN methods ---
  public function join(string $table, string $alias, string $on, string $type = 'INNER'): self
  {
    $this->joins[] = [
      'type'  => strtoupper($type),
      'table' => $table,
      'alias' => $alias,
      'on'    => $on
    ];

    return $this;
  }

  public function leftJoin(string $table, string $alias, string $on): self
  {
    return $this->join($table, $alias, $on, 'LEFT');
  }

  public function rightJoin(string $table, string $alias, string $on): self
  {
    return $this->join($table, $alias, $on, 'RIGHT');
  }

  public function innerJoin(string $table, string $alias, string $on): self
  {
    return $this->join($table, $alias, $on, 'INNER');
  }

  // --- WHERE clause ---
  /**
   * Add a WHERE condition. If the first argument is a closure,
   * it will be used to build a nested condition.
   */
  public function where($column, string $operator = null, $value = null, string $boolean = 'AND'): self
  {
    if (is_callable($column)) {
      // Build nested conditions using a new Query instance.
      $nested = new self();
      $column($nested);
      $clause = '(' . $nested->buildWhereClause() . ')';

      if (strpos($clause, '.')) {
        die($clause);
        $clause = str_replace('.', '`.`', $clause);
      }

      $this->wheres[] = [
        'boolean' => $boolean,
        'clause'  => $clause,
        'params'  => $nested->getParams()
      ];
    } else {
      // Simple condition
      $this->wheres[] = [
        'boolean' => $boolean,
        'clause'  => "`$column` $operator ?",
        'params'  => [$value]
      ];
    }
    return $this;
  }

  public function orWhere($column, string $operator = null, $value = null): self
  {
    return $this->where($column, $operator, $value, 'OR');
  }

  // --- GROUP BY clause ---
  public function groupBy($columns): self
  {
    if (is_string($columns)) {
      $columns = [$columns];
    }
    $this->groups = array_merge($this->groups, $columns);
    return $this;
  }

  // --- HAVING clause ---
  public function having(string $column, string $operator, $value, string $boolean = 'AND'): self
  {
    $this->havings[] = [
      'boolean' => $boolean,
      'clause'  => "`$column` $operator ?",
      'params'  => [$value]
    ];
    return $this;
  }

  // --- ORDER BY clause ---
  public function orderBy(string $column, string $direction = 'ASC'): self
  {
    $this->orderBy[] = "`$column` " . strtoupper($direction);
    return $this;
  }

  // --- LIMIT and OFFSET ---
  public function limit(int $limit): self
  {
    $this->limit = $limit;
    return $this;
  }

  public function offset(int $offset): self
  {
    $this->offset = $offset;
    return $this;
  }

  // --- UNION clause ---
  public function union(self $builder, bool $all = false): self
  {
    $this->unions[] = [
      'type'    => $all ? 'UNION ALL' : 'UNION',
      'builder' => $builder
    ];
    return $this;
  }

  // --- New: WITH clause for recursive queries ---
  /**
   * Define a Common Table Expression (CTE).
   *
   * @param string $name The name of the CTE.
   * @param self $query The query that defines the CTE.
   * @param bool $recursive Whether this CTE is recursive.
   * @return self
   */
  public function with(string $name, self $query, bool $recursive = false): self
  {
    $this->ctes[] = [
      'name'      => $name,
      'query'     => $query,
      'recursive' => $recursive
    ];
    return $this;
  }

  /**
   * Build the WITH clause for the query.
   *
   * @return string The complete WITH or WITH RECURSIVE clause.
   */
  protected function buildWithClause(): string
  {
    if (empty($this->ctes)) {
      return '';
    }
    $cteParts = [];
    $isRecursive = false;

    foreach ($this->ctes as $cte) {
      if ($cte['recursive']) {
        $isRecursive = true;
      }

      $cteParts[] = "`{$cte['name']}` AS (" . $cte['query']->getQuery() . ")";
    }

    $prefix = $isRecursive ? 'WITH RECURSIVE ' : 'WITH ';

    return $prefix . implode(', ', $cteParts) . ' ';
  }

  // --- Internal: Build WHERE clause ---
  protected function buildWhereClause(): string
  {
    if (empty($this->wheres)) {
      return '';
    }

    $clause = '';

    foreach ($this->wheres as $index => $where) {

      $clause .= ($index === 0 ? '' : ' ' . $where['boolean'] . ' ') . $where['clause'];
    }

    if (strpos($clause, '.')) {
      $clause = str_replace('.', '`.`', $clause);
    }



    return $clause;
  }

  // --- Internal: Build HAVING clause ---
  protected function buildHavingClause(): string
  {
    if (empty($this->havings)) {
      return '';
    }

    $clause = '';

    foreach ($this->havings as $index => $having) {
      $clause .= ($index === 0 ? '' : ' ' . $having['boolean'] . ' ') . $having['clause'];
    }

    return $clause;
  }

  // --- Collect parameters from CTEs, WHERE, and HAVING clauses ---
  public function getParams(): array
  {
    $params = [];
    // Include parameters from CTE definitions first.
    foreach ($this->ctes as $cte) {
      $params = array_merge($params, $cte['query']->getParams());
    }

    // Then include parameters from WHERE clauses.
    foreach ($this->wheres as $where) {
      $params = array_merge($params, $where['params']);
    }

    // And finally, parameters from HAVING clauses.
    foreach ($this->havings as $having) {
      $params = array_merge($params, $having['params']);
    }

    return $params;
  }

  // --- Build the complete SELECT query ---
  public function getQuery(): string
  {
    // Trigger before-build hooks, if any.
    foreach (self::$beforeBuildHooks as $hook) {
      $hook($this);
    }

    $withClause = $this->buildWithClause();
    $selectClause = empty($this->select) ? '*' : implode(', ', $this->select);
    $query = $withClause . "SELECT {$selectClause} FROM `{$this->table}`";

    // Append JOIN clauses.
    if (!empty($this->joins)) {

      foreach ($this->joins as $join) {
        $alias = $join['alias'] ? " AS `{$join['alias']}`" : '';
        $query .= " {$join['type']} JOIN `{$join['table']}`{$alias} ON {$join['on']}";
      }
    }

    // Append WHERE clause.
    $whereClause = $this->buildWhereClause();
    if ($whereClause !== '') {
      $query .= " WHERE {$whereClause}";
    }

    // Append GROUP BY clause.
    if (!empty($this->groups)) {
      $query .= " GROUP BY " . implode(', ', $this->groups);
    }

    // Append HAVING clause.
    $havingClause = $this->buildHavingClause();
    if ($havingClause !== '') {
      $query .= " HAVING {$havingClause}";
    }

    // Append ORDER BY clause.
    if (!empty($this->orderBy)) {
      $query .= " ORDER BY " . implode(', ', $this->orderBy);
    }

    // Append LIMIT and OFFSET.
    if (!is_null($this->limit)) {
      $query .= " LIMIT {$this->limit}";
    }
    if (!is_null($this->offset)) {
      $query .= " OFFSET {$this->offset}";
    }

    // Append UNION clauses, if any.
    if (!empty($this->unions)) {
      $mainQuery = $query;
      foreach ($this->unions as $union) {
        $unionQuery = $union['builder']->getQuery();
        $mainQuery .= " " . $union['type'] . " " . $unionQuery;
      }
      $query = $mainQuery;
    }

    // Trigger after-build hooks, if any.
    foreach (self::$afterBuildHooks as $hook) {
      $hook($this, $query);
    }

    return $query;
  }

  /**
   * Execute the query using a provided Database instance.
   *
   * @param Database $db The Database instance.
   * @return array The query results as an array of objects.
   */
  public function execute(): array
  {
    $app      = Application::getInstance();
    $db       = $app->db;
    $conf     = $app->conf->database->query;
    $query    = $this->getQuery();
    $params   = $this->getParams();
    $cacheKey = md5($query . serialize($params));

    if ($conf->cache && isset(self::$cache[$cacheKey])) {
      $cacheEntry = self::$cache[$cacheKey];

      if (time() - $cacheEntry['time'] < $conf->cacheTTL) {
        return $cacheEntry['result'];
      }
    }

    $result = $db->getObjects($query, $params);

    if ($conf->cache) {
      self::$cache[$cacheKey] = [
        'time'   => time(),
        'result' => $result
      ];
    }

    return $result;
  }

  // --- Event Hooks Registration ---
  public static function registerBeforeBuild(callable $callback): void
  {
    self::$beforeBuildHooks[] = $callback;
  }

  public static function registerAfterBuild(callable $callback): void
  {
    self::$afterBuildHooks[] = $callback;
  }
}

/*

--

How to Use These Builders
SELECT Example with Joins, Nested Conditions, and Unions:

use Adept\Application\Query;

// Build the main query.
$qb = Query::table('users')
    ->select(['id', 'name', 'email'])
    ->leftJoin('profiles', 'p', 'users.id = p.user_id')
    ->where('status', '=', 'active')
    ->orWhere(function($query) {
        // Nested conditions: (age > 30 AND role = 'admin')
        $query->where('age', '>', 30)
              ->where('role', '=', 'admin');
    })
    ->groupBy('department')
    ->having('COUNT(id)', '>', 5)
    ->orderBy('name', 'ASC')
    ->limit(10)
    ->offset(0)
    ->enableCache(true, 600);

// Optionally add a union:
$qb2 = Query::table('users')
    ->select(['id', 'name', 'email'])
    ->where('status', '=', 'pending');
$qb->union($qb2);

// Execute (assuming $db is your Database instance)
$results = $qb->execute($db);
INSERT Example:

use Adept\Application\InsertQuery;

$insert = InsertQuery::into('users')
    ->values(['name' => 'Alice', 'email' => 'alice@example.com'])
    ->values(['name' => 'Bob', 'email' => 'bob@example.com']);
$lastId = $insert->execute($db);
UPDATE Example:

use Adept\Application\UpdateQuery;

$update = UpdateQuery::table('users')
    ->set('email', 'newemail@example.com')
    ->where('id', '=', 42);
$success = $update->execute($db);
DELETE Example:

use Adept\Application\DeleteQuery;

$delete = DeleteQuery::from('users')
    ->where('id', '=', 42);
$success = $delete->execute($db);
Summary
This implementation brings together many advanced features into a suite of query builder classes. You can extend or tweak the behavior (for example, adding named parameters, more advanced subquery support, or integrating with an ORM layer) without rewriting your entire codebase. This modular design makes your database interactions safer, more maintainable, and far more expressive.

Feel free to ask if you need further enhancements or have questions about any specific part!

How It Works
Defining a CTE:
Use the new with() method to define a CTE. For example, to create a recursive query:

$cte = Query::table('categories')
    ->select(['id', 'parent_id', 'name'])
    ->where('parent_id', '=', 0);

$recursive = Query::table('categories')
    ->select(['c.id', 'c.parent_id', 'c.name'])
    ->join('cte', '', 'categories.parent_id = cte.id')
    ->where('categories.parent_id', '>', 0);

$mainQuery = Query::table('cte')
    ->with('cte', $cte->union($recursive), true)
    ->select(['*']);
This example defines a recursive CTE named cte and then uses it in the main query.
Building the Query:
The getQuery() method calls buildWithClause() to prepend the WITH (or WITH RECURSIVE) clause based on the CTE definitions. All other parts of the query are assembled as before.
Parameter Handling:
The getParams() method now merges parameters from the CTE definitions along with those from WHERE and HAVING clauses.
This updated Query now supports recursive queries along with all previous functionality, making it a flexible tool for building complex SQL queries.

Feel free to ask if you need further modifications or explanations!
*/