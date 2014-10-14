<?php
require_once 'PHP/SqlObject.php';


if (isset($_FILES['file'])) {
    $file = fopen($_FILES["file"]["tmp_name"], 'r');
    $CSVData = array();

    while (($line = fgetcsv($file)) !== FALSE) {
        //$line is an array of the csv elements
        array_push($CSVData, $line);
    }
    fclose($file);
    unset($CSVData[0]);
}

?>

<div class = "push-right">

    <div class = "filter">
        <div class = "headElement">
            <img src="IMG/calander.png" alt="Calander" class = "inline-image">
            <h2 class = "inline-text">Specialisation</h2>
        </div>
    <span class = "filter-stream">
        <ul>
            <li class = "admin-controls extra-wide">View CSV</li>
            <?php if(isset($CSVData)) { ?>
            <li class = "admin-controls extra-wide">Add all to Database</li>
            <li class = "admin-controls">Edit</li>
            <li class = "admin-controls">Delete</li>
            <?php } ?>
        </ul>
    </span>
    </div>

    <div class="contentWrapper">
        <table id = "InformationTable">
            <thead>
            <tr>
                <th class = "check-Small check-head"><input type="checkbox"></th>
                <th class = "tableHead">Student ID <span class = "arrow-down"></span></th>
                <th class = "tableHead">Student Name<span class = "arrow-down"></span></th>
                <th class = "tableHead">Streams<span class = "arrow-down"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($CSVData)) {
                for ($i = 1; $i < count($CSVData) + 1; $i++) {
                    echo '<tr>';
                    echo '<td class="check-Small"><input type = "checkbox" ></td>';
                    echo '<td class = "data-item">' . $CSVData[$i][0] . '</td>';
                    echo '<td class = "data-item">' . $CSVData[$i][1] . '</td>';
                    echo '<td class = "data-item">' . $CSVData[$i][2] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>