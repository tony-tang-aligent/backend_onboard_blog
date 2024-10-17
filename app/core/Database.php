<?php
namespace app\core;
use PDO;
use PDOException;

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $this->host = getenv('MYSQL_HOST');
        $this->user = getenv('MYSQL_USER');
        $this->pass = getenv('MYSQL_PASSWORD');
        $this->dbname = getenv('MYSQL_DATABASE');

        // Set the DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /** Prepare the SQL query
     * @param $sql
     * @return void
     */
    public function query($sql): void
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /** Bind the parameters
     * @param $param
     * @param $value
     * @param $type
     * @return void
     */
    public function bind($param, $value, $type = null): void
    {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /** Execute the prepared statement
     * @return void
     */
    public function execute() {
        try {
            return $this->stmt->execute(); // Execute the prepared statement
        } catch (PDOException $e) {
            echo 'Execution error: ' . $e->getMessage();
        }
    }

    /** Get the result set as an array of objects
     * @return mixed
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /** Get a single record as an object
     * @return mixed
     */
    public function single() {
        $this->execute(); // Execute the prepared statement
        return $this->stmt->fetch(PDO::FETCH_OBJ); // Fetch a single record as an object
    }
}

