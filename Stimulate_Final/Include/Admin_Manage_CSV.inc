<?php
require_once 'PHP/SqlObject.php';


if (isset($_FILES['file'])) {
    $file = fopen($_FILES["file"]["tmp_name"], 'r');
    $CSVData = array();
    $numberColumns = 0;
    while (($line = fgetcsv($file)) !== FALSE) {
        //$line is an array of the csv elements
        array_push($CSVData, $line);
        $numberColumns = count($line);
    }
    fclose($file);
    unset($CSVData[0]);
}

// If the page is access with an appropraite get variable
if(isset($_GET['action'])) { ?>
<script>
    $(document).ready(function(){
        LoadUserInteractions(<?php echo JSON_ENCODE($_GET['action']); ?>);
    });
</script>
<div class = "push-right">
<div class = "filter">
    <div class = "headElement">
        <img src="IMG/calander.png" alt="Calander" class = "inline-image">
        <h2 class = "inline-text">Specialisation</h2>
    </div>
    <span class = "filter-stream">
        <ul>
            <?php
            determineControls();
            ?>
        </ul>
    </span>
</div>

<div class="contentWrapper">
    <table id = "InformationTable">
        <thead>
        <tr>
            <?php
            determineTableHead();
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($CSVData, $numberColumns)) {
            determineBody($CSVData, $numberColumns);
        }
        ?>
        </tbody>
    </table>
</div>
<?php }

/*
 * Determines the appropriate html to put into the button control box
 * Echos html into browser
 */
function determineControls(){
    // Define all button options, and unset them as needed using the switch
    $buttonHtml = array('addData' => '<li class = "admin-controls">Add to All</li>', 'addCSV' => '<li class = "admin-controls">Add CSV</li>');

    // Depending on the action return the appropraite information
    switch($_GET['action']) {
        // If user wants to manage volunteers
        case null:
            // Default action. Display nothing
            $buttonHtml = array();
            break;
        default:
            if(isset($_POST)){
                if(count($_POST) > 0){
                    unset($buttonHtml['addCSV']);
                } else {
                    unset($buttonHtml['addData']);
                }
            } else {
                unset($buttonHtml['addData']);
            }
            break;
    }

    // Echo html in as needed
    foreach($buttonHtml as $button){
        echo $button;
    }
}

function determineTableHead(){
    // Define all table head options, later the index chooses the correct one.
    $tableHeadHtml = array('manageVolunteer' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                    <th class = "tableHead">Student Number<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Name<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Streams<span class = "arrow-down"></span></th>',
        'manageStaff' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                    <th class = "tableHead">Staff Number<span class = "arrow-down"></span></th>
                    <th class = "tableHead">Name<span class = "arrow-down"></span></th>',
        'manageShift' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID<span class = "arrow-down"></span></th>
                <th class = "tableHead">Stream<span class = "arrow-down"></span></th>
                <th class = "tableHead">Day<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time<span class = "arrow-down"></span></th>
                <th class = "tableHead">Duration<span class = "arrow-down"></span></th>',
        'manageAbsent' => '<th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID <span class = "arrow-down"></span></th>
                <th class = "tableHead">Student Name<span class = "arrow-down"></span></th>
                <th class = "tableHead">Time Absent<span class = "arrow-down"></span></th>
                <th class = "tableHead">End Absent Time<span class = "arrow-down"></span></th>
                <th class = "tableHead">Reason<span class = "arrow-down"></span></th>'
    );
    $index = '';

    // Echo html in as needed
    echo $tableHeadHtml[$_GET['action']];
    return true;
}

function determineBody($CSVData, $numberColumns) {
    if (isset($CSVData)) {
        for ($i = 1; $i < count($CSVData) + 1; $i++) {
            echo '<tr>';
            echo '<td class="check-Small"><input type = "checkbox" ></td>';
            for($j = 0; $j < $numberColumns; $j++){
                echo '<td class = "data-item">' . $CSVData[$i][$j] . '</td>';
            }
            echo '</tr>';
        }
    }

}
?>
