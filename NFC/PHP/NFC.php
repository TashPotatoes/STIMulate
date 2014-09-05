<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 31/07/14
 * Time: 2:01 PM
 */

namespace PHP;
require_once 'SqlObject.php';

class NFC {
    private $readerID;
    private $readerData;

    function __construct($readerID){
        $this->readerID = $readerID;
        $this->readerData = $this->GetLatestNFC();
    }

    private function GetLatestNFC(){
        $sqlObject = new SqlObject("SELECT * FROM stimulate.nfctouch WHERE nfctouch.readerID = :readerID AND nfctouch.Active = 1;   ", array($this->readerID));
        return $sqlObject->Execute();
    }

    private function DeactivateNFC(){
        $sqlObject = new SqlObject("UPDATE stimulate.nfctouch SET nfctouch.Active = 0 WHERE nfctouch.NfcID = :nfcID;", array($this->readerData[0]['NfcID']));
        $sqlObject->Execute();
    }

    public function GetReaderData(){
        if(isset($this->readerData[0])) {
            if(count($this->readerData[0]) > 0) {
                $this->DeactivateNFC();
            }
        }
        return $this->readerData;
    }
} 