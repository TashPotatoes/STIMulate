<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 21/07/14
 * Time: 12:49 PM
 */

namespace PHP;
require_once 'MySQL.php';

class SqlObject extends MySQL{
    private $QueryResults;
    private $id;

    // Constructor
    public function __construct($sqlStatement, $parameters = array()){
        parent::__construct($sqlStatement, $parameters);
    }

    // Executes the SELECT SQL.
    public function Execute(){
        // Preparing Statement
        $databaseObjects = parent::Prepare();

        $connection = $databaseObjects['connection'];
        $databaseQuery = $databaseObjects['databaseQuery'];

        // PDO executing the statement
        $databaseQuery->execute();

        // Gets the inserted ID if required
        $this->id = $connection->lastInsertId();

        // Retrieves the database Query
        $this->QueryResults = $databaseQuery->fetchAll(\PDO::FETCH_ASSOC);

        // Committing sql
        $connection->commit();

        // Fetching information
        return $this->QueryResults;
    }

    public function GetQueryResults(){
        return $this->QueryResults;
    }

    public function GetID(){
        return $this->id;
    }
} 