<?php

class Database {
    private $host = 'mysql-blog';
    private $user = 'user';
    private $pass = 'userpassword';
    private $dbname = 'mydatabase';

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {

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

    // Prepare the SQL query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind the parameters
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute() {
        try {
            return $this->stmt->execute(); // Execute the prepared statement
        } catch (PDOException $e) {
            echo 'Execution error: ' . $e->getMessage();
        }
    }

    // Get the result set as an array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get a single record as an object
    public function single() {
        $this->execute(); // Execute the prepared statement
        return $this->stmt->fetch(PDO::FETCH_OBJ); // Fetch a single record as an object
    }

}

