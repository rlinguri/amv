<?php
/****
 * @package   AMV
 * @author    Roderic Linguri <rod@linguri.com>
 * @copyright 2015 Linguri LLC
 * @license   MIT (This header MUST remain intact)
 */

abstract class AMVObject
{

    /***
     * set object properties
     * @param:  assoc
     * @return: void
     */
    public function setProperties()
    {
        $args = func_get_args();

        if (count($args) > 0) {

            foreach ($args as $key=>$val) {

                $this->$key = $val;

            }

        }

    }

}

abstract class AMVDatabase
{

    /* @property PDO connection */
    protected $pdo;

    /* @property the PDO statement handle */
    protected $stmt;

/**** ADD THE FOLLOWING TO EXTENDED CLASSES

    // database singleton
    protected static $db;

    // singleton getter
    public static function db()
    {
        if (!isset(self::$db)) {

            self::$db = new self();

        }

        return self::$db;

    }

    // constructor
    public function __construct()
    {

        $this->pdo = new PDO('mysql:host=localhost;dbname=testdb','root','password');

        // production
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        // development
        // $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

****/

    /***
     * set the statement
     * @param:  string (prepared statement)
     * @return: bool
     */
    public function setStmt()
    {
        $args = func_get_args();

        if (count($args) > 0) {

            $this->stmt = $this->pdo->prepare(array_shift($args));

        }

        // any extraneous parameters are assumed to be statement parameters
        if (count($args) > 0) {

            $this->setArgs($args);

        }

        if (isset($this->stmt)) {

            return true;

        } else {

            return false;

        }

    }

    /***
     * execute statement with passed in arguments
     * @param:  string, [string]
     * @return: bool
     */
    public function executeStmt()
    {

        $args = func_get_args();

        if (isset($this->stmt)) {

            if (count($args) > 0) {

                if ($this->stmt->execute($args) == true) {

                    return true;

                } else {

                    return false;

                }

            } else {

                if ($this->stmt->execute() == true) {

                    return true;

                } else {

                    return false;

                }

            }

        } else {

            return false;

        }

    }

    /***
     * fetch a single record
     * @param:  void
     * @return: assoc
     */
    public function fetchRecord()
    {

        return $this->stmt->fetch(PDO::FETCH_ASSOC);

    }

    /***
     * fetch multiple records
     * @param:  void
     * @return: array of assoc
     */
    public function fetchRecords()
    {

        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /***
     * get the primark key of the last record inserted
     * @param:  void
     * @return: int
     */
    public function lastInsertId()
    {

        return $this->pdo->lastInsertId();

    }

}

abstract class AMVModel
{

    protected $db;

    protected $table;

    protected $columns;

    protected $key;

/**** ADD THE FOLLOWING TO EXTENDED CLASSES

    // model singleton
    protected static $ms;

    // singleton getter
    public static function ms()
    {
        if (!isset(self::$ms)) {

            self::$ms = new self();

        }

        return self::$ms;

    }

    // constructor
    public function __construct()
    {

        $this->db = YourDatabaseClass::db();

        $this->table = 'table_name';

        $this->colums = array(
            'col1' => 'INTEGER',
            'col2' => 'TEXT',
            'col3' => 'DOUBLE'
        );

        $this->key = 'col1';

    }

****/

    /***
     * fetch all records
     * @param:  void
     * @return: assoc
     */
    public function fetchAll()
    {

        $this->db->setStmt('SELECT * FROM '.$this->table);

        $this->db->executeStmt();

        return $this->db->fetchRecords();

    }

    /***
     * fetch a single record
     * @param:  integer
     * @return: assoc
     */
    public function fetchRecordFromId($id)
    {

        $this->db->setStmt('SELECT * FROM '.$this->table.' WHERE '.$this->key.' = ? LIMIT 1');

        $this->db->executeStmt($id);

        return $this->db->fetchRecord();

    }

    /***
     * fetch a range of records
     * @param:  integer
     * @return: assoc
     */
    public function fetchRange($startId, $endId)
    {

        // simplify statement
        $low = $startId - 1;
        $high = $endId + 1;

        $this->db->setStmt('SELECT * FROM '.$this->table.' WHERE '.$this->key.' > ? AND '.$this->key.' < ?');

        $this->db->executeStmt($low,$high);

        return $this->db->fetchRecords();

    }

    /***
     * inserts a record
     * @param:  assoc
     * @return: integer
     */
    public function insertRecord($assoc)
    {

        $keys = '';

        $vphs = '';

        $k = 1;

        foreach (array_keys($assoc) as $key) {

            $keys .= $key;

            $vphs .= '?';

            echo $k.PHP_EOL;

            if ($k < count($assoc)) {

                $keys .= ',';

                $vphs .= ',';

            }

            $k++;

        }

        $sql = 'INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$vphs.')';

        $this->db->setStmt($sql);

        call_user_func_array(array($this->db, 'executeStmt'), array_values($assoc));

        return $this->db->lastInsertId();

    }

}

abstract class AMVView
{

}

abstract class AMVController
{

}