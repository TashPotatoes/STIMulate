<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<script src="../dist/glpk.min.js"></script>	
</head>
<body>
<?php
    require '../../PHP/functions.php'; 
	require_once '../../php/databaseAPI.php';
    require_once '../../php/SqlObject.php';
    require '../../php/uac.php';
	
	$stream = "IT"; //TODO:DONE create UI with a button, where this variable gets its value from
	if(isset($_POST['stream'])) {
		$stream =  $_POST['stream'];
		echo "<p>Current Stream: " . $stream . "</p>";
	} else {
?>
<form name="streamSelect" action="test.php" method="post">
<input type="radio" name="stream" id="stream-it"value="IT"><label for="stream-it">IT</label><br/>
<input type="radio" name="stream" id="stream-ma"value="MA"><label for="stream-ma">Maths</label><br/>
<input type="radio" name="stream" id="stream-sc"value="SC"><label for="stream-sc">Science</label><br/>
<input type="radio" name="stream" id="stream-dh"value="DH"><label for="stream-dh">Duty Host</label><br/>
<input type="submit" value="Select Stream">
</form>
<?php
}

	// Generate array of studentsID, Index of studentID in array will be used to generate the input CPLEX string
	$studentListRS = new \PHP\SqlObject("SELECT `student_ID`, `day`, `shift_time` FROM preferences GROUP BY `student_ID ORDER BY `student_ID` ASC WHERE  `stream` = :stream;", array($stream));
    $studentListRS->Execute();
	$studentArray = array();
	$studentTotal = 0;
	foreach($studentListRS as $row) {
		$studentArray[$studentTotal] = $row['student_ID'];
		$studentTotal++;
	}
	$studentHours = array(); //TODO:DONE create hours in db and then uncomment code below and edit for loop for generating relevant constraints
	$studentHoursRS = new \PHP\SqlObject("SELECT `student_ID`, `hours`, FROM facilitator_maxx_hours WHERE `stream` = :stream GROUP BY `student_ID` ORDER BY `student_ID` asc", array($stream));
    $studentHoursRS->Execute();
	foreach($studentHoursRS as $row) {
		$studentIndex = array_search($row['student_ID'],$studentList,true);
		$studentHours[studentIndex] = $row['hours'];
	}
	
			
	// Generates array with ith student and jth shift, where j is calculated by day + shift
	$shiftTotal = 8; //TODO: maybe change to a value that calculated by counting number of columns in db after the stream field or whatever it is
	$startTime = 9;
	$preferencesRS = new \PHP\SqlObject("SELECT `student_ID`, `day`, `shift_time` FROM preferences  WHERE  `stream` = :stream;", array($stream));
    $preferencesRS->Execute();
	$prefArray = array();
	foreach($preferencesRS as $row) {
		$studentIndex = array_search($row['student_ID'],$studentList,true);
		for ($i = 0; $i < $shiftTotal; $i++){
			$prefArray[$studentIndex][$row['day']+$i] = $row["'" . ($i + startTime) % 12 . "'"];
		}
	}

	// iterate over each persons preferences to create objective function
	$objective = "\* Objective function *\ *\ \n Maximize \n obj:";
	for ($person = 0; $person < $studentTotal; $person++){
		for ($shift = 0; $shift < $shiftTotal; $shift++){
			$objective .= " +" . $prefArray[$person][$shift] . " x" . $person . "_" . $shift;
		}
	}

	// iterate over each persons to make sure each persons total weekly hours doesn't exceed their specified hours for that stream
	$constraint = "\n  \* Constraints *\ \n Subject To \n";
	for ($person = 0; $person < $studentTotal; $person++){
		$constraint .= "person_" . $person . ":"; 
		for ($shift = 0; $shift < $shiftTotal; $shift++){
			$constraint .=  " +x" . $person . "_" . $shift;
		}
		//$constraint .= " = " . 2 . "\n"; // TODODONE: after db is changed, changed this line to: 
		$constraint .= " = " + $studentHours[$person] + "\n"; // decide whether <= or =
	}
	
	// iterate over each shift to make sure each shift has the specified number of people
	$desk = array ( 1, 1, 2, 2, 2, 2, 1, 1);
	for ($shift = 0; $shift < $shiftTotal; $shift++){
		$constraint .= "shift_" + $shift +":";
		for ($person = 0; $person < $studentTotal; $person++){
			$constraint .=  " +x" . $person . "_" . $shift;
		}
		$constraint .= " <= " . $desk[$shift] . "\n";
	}
	
	
	// TODO: add int 1, 0 field for each stream called new to db, once added, uncomment the following code 
	/*$newTotal = new \PHP\SqlObject("SELECT COUNT(*) FROM preferences WHERE new = :new AND stream = :stream;", array(1, $stream));
    $newTotal->Execute();*/
	
	// ensure new plfs are paired with old plfs.If y in newPLFa is true then b 
	// is effective, otherwise large m will make restraint redundant
	// Modelled on A - 1 + my < m,  1 - B - my <= 0
	$newTotal = 5; //TODO: delete this once db field new has been added
	$m = 10000; // arbitrarily large amount
	$constraintNewA = "";
	$constraintNewB = "";
	for ($shift = 0; $shift < $shiftTotal; $shift++){
		$constraintNewA .= "newPLFa_" . $shift . ": "; 
		$constraintNewB .= "newPLFb_" . $shift . ": 1- ";
		
		for ($person = 0; $person < $newTotal; $person++){
			$constraintNewA .=  " x" . $person . "_" . $shift;
		}
		for ($person = $newTotal; $person < $studentTotal; $person++){
			$constraintNewB .=  " -x" . $person . "_" . $shift;
		}
		$constraintNewA .= " -1 +" . $m . "y" . $shift . " < " . $m . "\n";
		$constraintNewB .= " -" . $m . "y" . $shift . " <= " . 0 . "\n";
		
		$constraint .= $constraintNewA + $constraintNewB;
	}
		
	// Ensures all decision variables are binary (ie less than 1 and integer)
	$bounds = "\n \* Variable bounds *\ \n Bounds \n";
	$integers = "\n	\* Integer definitions *\ \n General \n ";	
	for ($shift = 0; $shift < $shiftTotal; $shift++){
		for ($person = 0; $person < $studentTotal; $person){
			$bounds .= " x" . $person . "_" . $shift . " <= 1 \n ";
			$integers .=" x" . $person . "_" . $shift;
		}
		$bounds .= " y" . $shift . " <= 1 \n";
	}

	// Collect all the strings to generate the input string
	$input = $objective . $constraint . $bounds . $integers . " End";
	
	//TODO: pass input string directly to algorithm, may need to change.
	
	echo $input;
	//echo "<textarea id='source' cols='50' rows='10' placeholder = 'two' >";
?>	

	<textarea id="source" cols="50" rows="10"> 
\* Objective function *\
Minimize
obj: +17 x1_1 +23 x2_1 +16 x3_1 +19 x4_1 +18 x5_1 +21 x1_2 +16 x2_2 +20 x3_2 +19 x4_2 +19 x5_2 +22 x1_3 +21 x2_3 +16 x3_3 +22 x4_3 +15 x5_3 +18 x1_4 +16 x2_4 +25 x3_4 +22 x4_4 +15 x5_4 +24 x1_5 +17 x2_5
+24 x3_5 +20 x4_5 +21 x5_5 +15 x1_6 +16 x2_6 +16 x3_6 +16 x4_6 +25 x5_6 +20 x1_7 +19 x2_7 +17 x3_7 +19 x4_7 +16 x5_7 +18 x1_8 +25 x2_8 +19 x3_8 +17 x4_8 +16 x5_8 +19 x1_9 +18 x2_9 +19 x3_9 +21 x4_9 +23 x5_9
+18 x1_10 +21 x2_10 +18 x3_10 +19 x4_10 +15 x5_10 +16 x1_11 +17 x2_11 +20 x3_11 +25 x4_11 +22 x5_11 +22 x1_12 +15 x2_12 +16 x3_12 +23 x4_12 +17 x5_12 +24 x1_13 +25 x2_13 +17 x3_13 +25 x4_13 +19 x5_13 +24 x1_14
+17 x2_14 +21 x3_14 +25 x4_14 +22 x5_14 +16 x1_15 +24 x2_15 +24 x3_15 +25 x4_15 +24 x5_15

\* Constraints *\
Subject To
one_1: +x1_1 +x2_1 +x3_1 +x4_1 +x5_1 = 1
one_2: +x1_2 +x2_2 +x3_2 +x4_2 +x5_2 = 1
one_3: +x1_3 +x2_3 +x3_3 +x4_3 +x5_3 = 1
one_4: +x1_4 +x2_4 +x3_4 +x4_4 +x5_4 = 1
one_5: +x1_5 +x2_5 +x3_5 +x4_5 +x5_5 = 1
one_6: +x1_6 +x2_6 +x3_6 +x4_6 +x5_6 = 1
one_7: +x1_7 +x2_7 +x3_7 +x4_7 +x5_7 = 1
one_8: +x1_8 +x2_8 +x3_8 +x4_8 +x5_8 = 1
one_9: +x1_9 +x2_9 +x3_9 +x4_9 +x5_9 = 1
one_10: +x1_10 +x2_10 +x3_10 +x4_10 +x5_10 = 1
one_11: +x1_11 +x2_11 +x3_11 +x4_11 +x5_11 = 1
one_12: +x1_12 +x2_12 +x3_12 +x4_12 +x5_12 = 1
one_13: +x1_13 +x2_13 +x3_13 +x4_13 +x5_13 = 1
one_14: +x1_14 +x2_14 +x3_14 +x4_14 +x5_14 = 1
one_15: +x1_15 +x2_15 +x3_15 +x4_15 +x5_15 = 1
lim_1: +8 x1_1 +15 x1_2 +14 x1_3 +23 x1_4 +8 x1_5 +16 x1_6 +8 x1_7 +25 x1_8 +9 x1_9 +17 x1_10 +25 x1_11 +15 x1_12 +10 x1_13 +8 x1_14 +24 x1_15 <= 36
lim_2: +15 x2_1 +7 x2_2 +23 x2_3 +22 x2_4 +11 x2_5 +11 x2_6 +12 x2_7 +10 x2_8 +17 x2_9 +16 x2_10 +7 x2_11 +16 x2_12 +10 x2_13 +18 x2_14 +22 x2_15 <= 34
lim_3: +21 x3_1 +20 x3_2 +6 x3_3 +22 x3_4 +24 x3_5 +10 x3_6 +24 x3_7 +9 x3_8 +21 x3_9 +14 x3_10 +11 x3_11 +14 x3_12 +11 x3_13 +19 x3_14 +16 x3_15 <= 38
lim_4: +20 x4_1 +11 x4_2 +8 x4_3 +14 x4_4 +9 x4_5 +5 x4_6 +6 x4_7 +19 x4_8 +19 x4_9 +7 x4_10 +6 x4_11 +6 x4_12 +13 x4_13 +9 x4_14 +18 x4_15 <= 27
lim_5: +8 x5_1 +13 x5_2 +13 x5_3 +13 x5_4 +10 x5_5 +20 x5_6 +25 x5_7 +16 x5_8 +16 x5_9 +17 x5_10 +10 x5_11 +10 x5_12 +5 x5_13 +12 x5_14 +23 x5_15 <= 33

\* Variable bounds *\
Bounds
x1_1 <= 1
x2_1 <= 1
x3_1 <= 1
x4_1 <= 1
x5_1 <= 1
x1_2 <= 1
x2_2 <= 1
x3_2 <= 1
x4_2 <= 1
x5_2 <= 1
x1_3 <= 1
x2_3 <= 1
x3_3 <= 1
x4_3 <= 1
x5_3 <= 1
x1_4 <= 1
x2_4 <= 1
x3_4 <= 1
x4_4 <= 1
x5_4 <= 1
x1_5 <= 1
x2_5 <= 1
x3_5 <= 1
x4_5 <= 1
x5_5 <= 1
x1_6 <= 1
x2_6 <= 1
x3_6 <= 1
x4_6 <= 1
x5_6 <= 1
x1_7 <= 1
x2_7 <= 1
x3_7 <= 1
x4_7 <= 1
x5_7 <= 1
x1_8 <= 1
x2_8 <= 1
x3_8 <= 1
x4_8 <= 1
x5_8 <= 1
x1_9 <= 1
x2_9 <= 1
x3_9 <= 1
x4_9 <= 1
x5_9 <= 1
x1_10 <= 1
x2_10 <= 1
x3_10 <= 1
x4_10 <= 1
x5_10 <= 1
x1_11 <= 1
x2_11 <= 1
x3_11 <= 1
x4_11 <= 1
x5_11 <= 1
x1_12 <= 1
x2_12 <= 1
x3_12 <= 1
x4_12 <= 1
x5_12 <= 1
x1_13 <= 1
x2_13 <= 1
x3_13 <= 1
x4_13 <= 1
x5_13 <= 1
x1_14 <= 1
x2_14 <= 1
x3_14 <= 1
x4_14 <= 1
x5_14 <= 1
x1_15 <= 1
x2_15 <= 1
x3_15 <= 1
x4_15 <= 1
x5_15 <= 1

\* Integer definitions *\
General
x1_1 x2_1 x3_1 x4_1 x5_1 x1_2 x2_2 x3_2 x4_2 x5_2 x1_3 x2_3 x3_3 x4_3 x5_3 x1_4 x2_4 x3_4 x4_4 x5_4 x1_5 x2_5 x3_5 x4_5 x5_5 x1_6 x2_6 x3_6 x4_6 x5_6 x1_7 x2_7 x3_7 x4_7 x5_7 x1_8
x2_8 x3_8 x4_8 x5_8 x1_9 x2_9 x3_9 x4_9 x5_9 x1_10 x2_10 x3_10 x4_10 x5_10 x1_11 x2_11 x3_11 x4_11 x5_11 x1_12 x2_12 x3_12 x4_12 x5_12 x1_13 x2_13 x3_13 x4_13 x5_13 x1_14 x2_14 x3_14 x4_14
x5_14 x1_15 x2_15 x3_15 x4_15 x5_15

End

    </textarea><br>
    <input type="submit" onclick="run()" />
    <pre id="log"/>

    <script>
    var start;
	var logNode = document.getElementById("log");
        var log = glp_print_func = function(value){
            var now = new Date();
	        var d = (now.getTime() - start.getTime()) / 1000;
	        logNode.appendChild(document.createTextNode(value + "\n"));
            if (d > 60) throw new Error("timeout");
	        console.log(value);
        };
		function javascriptToPHP(var  jsvar, var pageURL) { 
				 $.post(pageURL, {variable: jsVar}); 
		}
		
		function run(){
            start = new Date(); 
			logNode.innerText = "";
            var lp = glp_create_prob();
            glp_read_lp_from_string(lp, null, <?php echo $input; ?>); //document.getElementById("source").value);

            glp_scale_prob(lp, GLP_SF_AUTO);

            var smcp = new SMCP({presolve: GLP_ON});
            glp_simplex(lp, smcp);

            var iocp = new IOCP({presolve: GLP_ON});
            glp_intopt(lp, iocp);
			
			var results;
            //l og("obj: " + glp_mip_obj_val(lp));
            for(var i = 1; i <= glp_get_num_cols(lp); i++){
                // log(glp_get_col_name(lp, i)  + " = " + glp_mip_col_val(lp, i));
				var person = Integer.parseInt(glp_get_col_name(lp, i) [1]);
				var shift = Integer.parseInt(glp_get_col_name(lp, i) [3]);
				var value = Integer.parseInt(glp_mip_col_val(lp, i));
				results[person][shift]  = value; 
            }
			
			javascriptToPHP(results, 'test.php');
			
		}
        
    </script>

	<?php

		// Return the javascript array of person, shift and value 
		$results = $_POST['variable'];
		$entries = count($results);
		$shiftEntries = array( "9-10", "10-11", "11-12", "12-1", "1-2", "2-3", "3-4", "4-5");
		$dayEntries = array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
		
		
		// assume shift_ID goes from 0 to shiftnum,
		$sqlEntry = "INSERT INTO autogen_timetable (student_ID, shift_ID, stream, day, shift_time) VALUES";
		foreach ($results as $entry){
			$sqlEntry = "( `". array_search($entry[1], $studentArray)  . "`, " . $entry[2] . ", `" . $stream . "`"; 
			$sqlEtnry .= ", `"  . $dayEntries(floor($entry[3] / $shiftTotal)) . "`, `" . $shiftEntries[$entry[3] % $shiftTotal] . "` ), ";
			///TODO: check if final "," causes sql error and if variable names have spaces at the start
		}
		
		// add constructed table to database
		$automaticTable = new \PHP\SqlObject("$sqlEntry", array());
		$automaticTable->Execute();
			
	?>


</body>
</html>

