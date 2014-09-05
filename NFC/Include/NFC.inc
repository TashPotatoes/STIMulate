<?php
    require_once 'PHP/Student.php';

    if(isset($_POST['studentID'], $_POST['name'], $_POST['email'], $_POST['nfcTag'])) {
        $student = new \PHP\Student();
        $student->InsertStudentInformation(array('studentID' => $_POST['studentID'], 'name' => $_POST['name'], 'email' => $_POST['email'], 'nfcTag' => $_POST['nfcTag']));
    }
?>

<form method = "POST" action = "" class ="entryForm">
    <h1>NFC Sign in Form</h1>
    <fieldset>
        <legend>Reader ID</legend>
        <input type = "text" name = "readerID" value = "1" class = "entryFields" DISABLED>
    </fieldset>
</form>

<form method = "POST" action = "" class = "entryForm">
    <fieldset>
        <legend id = "signInLegend">Sign in Form</legend>
        <input type = "text" name = "studentID" class = "entryFields" placeholder="Student Number: eg. 00000000">
        <input type = "datetime-local" value = "<?php date_default_timezone_set('Australia/Sydney'); echo date('Y-m-d', time())?>T<?php echo date('H:i', time())?>" name = "timestamp" class = "entryFields">
        <input type = "text" name = "name" class = "entryFields" placeholder="Name: eg. Bear Bearington">
        <input type = "text" name = "email" class = "entryFields" placeholder="Email: eg. bear@student.qut.edu.au">
        <input type = "text" name = "facilitator" class = "entryFields" id = "facilitator" placeholder="Facilitators Name or Account: eg. Bear Bearington Senior" autocomplete='off'>
        <input type = "hidden" name = "nfcTag" value = "">
        <div id = "suggestions"></div>
        <select name = "campus" class = "entryFields">
            <option>Garden's Point Campus</option>
            <option>Kelvin Grove Campus</option>
            <option>Caboolture Campus</option>
        </select>
        <select name = "stream" class = "entryFields">
            <option>Information Technology</option>
            <option>Science</option>
            <option>Math</option>
        </select>
        <select name = "unit" class = "entryFields">
            <option>INB300</option>
            <option>INB104</option>
            <option>INB203</option>
        </select>

        <?php /* 
        <div class = "checkBoxDiv">
            <input type="checkbox" name="understandingLecCont" value="LecCont"><label>Understanding Lecture Content</label><br>
            <input type="checkbox" name="understandingLecNotes" value="lecNotes"><label>Understanding Lecture Notes</label><br>
            <input type="checkbox" name="understandingAssignments" value="Assignments"><label>Understanding Assignments</label><br>
            <input type="checkbox" name="examPrep" value="examPrep"><label>Exam Preparation</label><br>
            <input type="checkbox" name="exercises" value="exercises"><label>Exercises</label><br>
            <input type="checkbox" name="understandingTextBook" value="textbook"><label>Understanding Text Book</label><br>
            <input type="checkbox" name="Other" value="other"><label>Other</label><br>
        </div>
    </fieldset>
    <input type = "submit" class = "submitButton">
</form>
*/ ?>
<div id = "test">
</div>