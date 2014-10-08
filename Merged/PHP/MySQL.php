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
        $this->username = "stimdev";
        $this->password = "itisbest";
        $this->sqlStatement = $sqlStatement;
        $this->parameter = $parameter;
    }

    // Gets the PDO connection
    private function Connection(){
        $databaseConnection = new \PDO("mysql:host=stimulate.ceu1tvrd8kag.ap-southeast-2.rds.amazonaws.com;dbname=STIMulate", $this->username, $this->password);
        return $databaseConnection;
    }

    // Extracts potential parameters denoted by :String and returns them
    private function ExtractParameters(){
        $pattern = '/:[A-z 0-9 ]*/'; // Parameter format.
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