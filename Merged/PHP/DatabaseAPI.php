<?php

require_once "SqlObject.php";

if(isset($_POST["action"])){
    switch($_POST["action"]) {
        case "retrieveAllData":
            RetrieveAllData();
            break;
        default:
            echo "Invalid Database Request.";
    }
}

function RetrieveAllData(){
    // Retrieving the initial data from database
    $sqlObject = new \PHP\SqlObject("SELECT * FROM dataaggregator.data;");
    echo JSON_ENCODE($sqlObject->Execute());
}