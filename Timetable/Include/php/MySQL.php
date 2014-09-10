<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 20/07/14
 * Time: 2:12 PM
 */

namespace PHP;


class MySQL {
    private $hostname;
    private $database;
    private $username;
    private $password;
    private $sqlStatement;
    private $parameter;

    public function __construct($sqlStatement, $parameter = array()){
/*        $this->hostname = "10.1.1.2";
        $this->database = "stimulate";
        $this->username = "root";
        $this->password = "";*/

        $this->hostname = "stimulate.ceu1tvrd8kag.ap-southeast-2.rds.amazonaws.com";
        $this->database = "stimualte";
        $this->username = "stimdev";
        $this->password = "itisbest";
        $this->sqlStatement = $sqlStatement;
        $this->parameter = $parameter;
    }

    // Gets the PDO connection
    private function Connection(){
           // $databaseConnection = new \PDO("mysql:host = $this->hostname; dbname = $this->database", $this->username, $this->password);
            $databaseConnection = new \PDO("mysql:host=stimulate.ceu1tvrd8kag.ap-southeast-2.rds.amazonaws.com;dbname=stimulate", $this->username, $this->password);
            return $databaseConnection;
    }

    // Extracts potential parameters denoted by :String and returns them
    private function ExtractParameters(){
        $pattern = '/:[A-z]*/'; // Parameter format.
        $matches = null;

        // Return all matches
        preg_match_all($pattern, $this->sqlStatement, $matches);
        return $matches;
    }

    // Prepares PDO, and returns the object ready to be executed
    public function Prepare(){
        $connection = $this->Connection();
        $databaseQuery = $connection->prepare($this->sqlStatement);

        $connection->beginTransaction();

        $parameterNames = $this->ExtractParameters();

        // Binding values as necessary
        for($i = 0; $i < count($this->parameter); $i++) {
            $databaseQuery->bindValue($parameterNames[0][$i], $this->parameter[$i]);
        }

        // Creating database array
        $databaseObj['connection'] = $connection;
        $databaseObj['databaseQuery'] = $databaseQuery;
        return $databaseObj;
    }

    // Returns the SQL statement entered
    public function GetSqlStatement(){
        return $this->sqlStatement;
    }
} 