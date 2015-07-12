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

    /* @property array of arguments to pass to statement */
    protected $args;

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
     * set the statement arguments
     * @param:  string, [string]
     * @return: bool
     */
    public function setArgs()
    {
        $args = func_get_args();

        if (count($args) > 0) {

            $this->args = $args;

        } else {

            // NOTE: if no args are passed, any existing args are erased
            $this->args = null;

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

}

abstract class AMVView
{

}

abstract class AMVController
{

}