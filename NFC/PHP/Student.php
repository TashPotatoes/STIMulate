<?php
/**
 * Created by PhpStorm.
 * User: crazygravy89
 * Date: 31/07/14
 * Time: 6:24 PM
 */

namespace PHP;
require_once "SqlObject.php";

class Student {
    private $studentTag;
    private $studentInformation;

    public function __construct($studentTag = null){

        if($studentTag != null) {
            $this->studentTag = $studentTag;
            $this->studentInformation = $this->retrieveStudentInformation();
        }
    }

    private function retrieveStudentInformation(){
        $sqlObject = new SqlObject("SELECT * FROM stimulate.students WHERE students.studentTag = :studentTag;", array($this->studentTag));
        $studentInfo = $sqlObject->Execute();
        return $studentInfo;
    }

    public function GetStudentInformation(){
        return $this->studentInformation;
    }

    public function InsertStudentInformation($studentInformation){
        $this->studentTag = $studentInformation['nfcTag'];
        $this->studentInformation = $studentInformation;

        $sqlObject = new SqlObject("INSERT INTO stimulate.students (studentNumber, name, email, studentTag) VALUES (:studentNumber, :name, :email, :studentTag);",
            array($studentInformation['studentID'], $studentInformation['name'], $studentInformation['email'], $studentInformation['nfcTag']));

        $sqlObject->Execute();
    }
} 