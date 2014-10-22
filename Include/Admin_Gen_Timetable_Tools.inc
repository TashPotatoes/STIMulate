<?php  
function generateCPLEX($stream){
			// Generate all data for all students. Database houses multiple rows per student.
			$studentPrefSQL = new \PHP\SqlObject("SELECT * FROM preferences JOIN facilitators ON facilitators.student_id = preferences.user_id  WHERE stream = :stream ORDER BY new_" .$stream." DESC, user_id ASC, day ASC", array(strtoupper($stream)));  
			$studentPrefRS = $studentPrefSQL->Execute();		
			
			// Collects all unique students in both preferences and facilitators. Index of studentID in array will be used to generate the input CPLEX string.
			$studentListSQL = new \PHP\SqlObject("SELECT student_id FROM preferences JOIN facilitators ON facilitators.student_id = preferences.user_id  WHERE stream = :stream GROUP BY user_id ORDER BY new_" . $stream . " DESC, user_id ASC", array($stream));  
			$studentListRS = $studentListSQL->Execute();		
			
			
			echo "retrieving from database<br/>";
			//flush();
			$studentArray = array();
			$studentTotal = 0;
			foreach ($studentListRS as $row) {
				$studentArray[$studentTotal] = $row['student_id'];
				$studentTotal++;
			}
			
			if ($studentTotal == 0){
				echo "no input data in database";
			}
			else if ($studentTotal < 8*5){
				echo "you have " . $studentTotal . " students. It is recommended that you wait for more students to submit preferences<br/>";
			}
			
			$totalHours = 0;
			$studentHours = array(); //TODONE create hours in db and then uncomment code below and edit for loop for generating relevant constraints
			foreach($studentPrefRS as $row) {
				$studentIndex = array_search($row['user_id'], $studentArray,true);
				$studentHours[$studentIndex] = $row["hours_" . $stream];
				$totalHours += $row["hours_" . $stream];
			}
			
			echo "processing student inputs<br/>";		
			
			// Generates array with ith student and jth shift, where j is calculated by day + shift
			$shiftTotal = 8; //TODONE maybe change to a value that calculated by counting number of columns in db after the stream field or whatever it is
			$startTime = 9;
			$daysTotal = 5;
			$dayArray = array(0,1,2,3,4);
			$prefArray = array();
			
			// For each student, retrieve and add their preferences and then calculate days where no data was stored in database (to save space in database)
			foreach($studentArray as $student) {
				
				$daysWithData = array();
				$daysWithoutData =array();
				
				// Generate data for the current student. 
				$studentSQL = new \PHP\SqlObject("SELECT * FROM preferences JOIN facilitators ON facilitators.student_id = preferences.user_id  WHERE stream = :stream AND user_id = :user ORDER BY user_id ASC, day ASC", array($stream, $student));  
				$studentRS = $studentSQL->Execute();		
				$studentIndex = array_search($studentRS[0][0], $studentArray, true);
				
				// go through each row and add all data
				$index = 0;
				foreach ($studentRS as $row) {
				
					for ($j = 0; $j < $shiftTotal; $j++){
						$prefArray[$studentIndex][$row['day']*$shiftTotal + $j] = $row[ ($j + $startTime -1) % 12 + 1];
						$daysWithData[$index] = $row['day'];
						$index++;
					}
				}
				
				// check what days where not data was added and add null entries worth -50 to the simplex algorithm
				$daysWithoutData = array_diff($dayArray, $daysWithData) ;
				foreach ($daysWithoutData as $day){
					for ($j = 0; $j < $shiftTotal; $j++){
								$prefArray[$studentIndex][$day*$shiftTotal + $j] = -50;
					}
				}
			}
			
			// iterate over each persons preferences to create objective function
			echo "generating model</br>";
			$objective = "\* Objective function *\  \n Maximize \n obj:";
			for ($person = 0; $person < $studentTotal; $person++){
				for ($shift = 0; $shift < $shiftTotal*$daysTotal; $shift++){
					$objective .= " +" . $prefArray[$person][$shift] . " x" . $person . "_" . $shift;
				}
			} 
				echo "starting constraints student hours</br>";
			// iterate over each persons to make sure each persons total weekly hours doesn't exceed their specified hours for that stream
			$constraint = "\n  \* Constraints *\ \n Subject To \n";
			for ($person = 0; $person < $studentTotal; $person++){
				$constraint .= "person_" . $person . ":"; 
				for ($shift = 0; $shift < $shiftTotal*$daysTotal; $shift++){
					$constraint .=  " +x" . $person . "_" . $shift;
				}
				$constraint .= " = " . ($studentHours[$person] ) . "\n"; // decide whether <= or =
			}
			echo "constraints num at desk</br>";
			// iterate over each shift to make sure each shift has the specified number of people
			$numAtDesk = array ( 1, 1, 2, 2, 2, 2, 1, 1);
			for ($shift = 0; $shift < $shiftTotal*$daysTotal; $shift++){
				$constraint .= "shift_" . $shift .":";
				for ($person = 0; $person < $studentTotal; $person++){
					$constraint .=  " +x" . $person . "_" . $shift;
				}
				$constraint .= " <= " . ($numAtDesk[$shift % count($numAtDesk)]) . "\n";
			}

			$newPLFConstraint = false;
			// check that there are more volunteered hours than time slots, to prevent no feasible solution
			if ($totalHours  > $shiftTotal*$daysTotal) {
				
				$newTotalSQL= new \PHP\SqlObject("SELECT * FROM facilitators JOIN preferences ON facilitators.student_id = preferences.user_id WHERE stream = :stream AND new_" . $stream . " = 1;", array(strtoupper($stream)));
				$newTotalRS = $newTotalSQL->Execute();
				$newTotal = count($newTotalRS);
				
				// Check if this contraint should be generated by comparing shifts volunteered with new people
				// if there is 150% shift coverage, 1/3 of shifts will have two people, so need newTotal to be less than (1/3 - error)*$shiftTotal*$daysTotal 
				// if there is 120% shift coverage, 1/5 of shifts will have two people. so need newTotal to be less than (1/5 - error) *$shiftTotal*$daysTotal 
				//if  (($totalHours / $shiftTotal/$daysTotal - 1 - $error)*$shiftTotal*$daysTotal) <= newTotal
				$constraintNewA = "";
				$constraintNewB = "";
					
				$error = 0.00;
				$minNew = 0.00;
				$minNew = floor(($totalHours)  - (1 + $error)*$shiftTotal*$daysTotal); 
				echo " total hours is $totalHours ";
				echo "new min is $minNew and new plfs is $newTotal";
				if  ($minNew  >= $newTotal) {
					
					echo "constraints new plfs with old plfs</br>";
					$newPLFConstraint = true;
					// ensure new plfs are paired with old plfs.If y in newPLFa is true then b 
					// is effective, otherwise large m will make restraint redundant
					// Modelled on A - 1 + my < m,  1 - B - my <= 0
					$m = 10000; // arbitrarily large amount
					$constraintNewA = "";
					$constraintNewB = "";
					for ($shift = 0; $shift < $shiftTotal*$daysTotal; $shift++){
						$constraintNewA .= "newPLFa_" . $shift . ": "; 
						$constraintNewB .= "newPLFb_" . $shift . ": 1- ";
						
						for ($person = 0; $person < $newTotal; $person++){
							$constraintNewA .=  " +x" . $person . "_" . $shift;
						}
						for ($person = $newTotal; $person < $studentTotal; $person++){
							$constraintNewB .=  " -x" . $person . "_" . $shift;
						}
						
						$constraintNewA .= " -1 +" . $m . "y" . $shift . " < " . $m . "\n";
						$constraintNewB .= " -" . $m . "y" . $shift . " <= " . 0 . "\n";
						
						$constraint .= $constraintNewA . $constraintNewB;
					}
				} else {
					echo "not enough students hours to ensure new PLF's are with old PLFs <br/>";
				}
			} else {
				echo "not enough students hours to ensure new PLF's are with old PLFs <br/>";
			}
			echo "working5<br/>";

			echo "ensure variables are binary </br>";	
			// Ensures all decision variables are binary (ie less than 1 and integer)
			$bounds = "\n \* Variable bounds *\ \n Bounds \n";
			$integers = "\n	\* Integer definitions *\ \n General \n";	
			for ($shift = 0; $shift < $shiftTotal*$daysTotal; $shift++){
			
				for ($person = 0; $person < $studentTotal; $person++){
					$bounds .= " x" . $person . "_" . $shift . " <= 1 \n";
					$integers .=" x" . $person . "_" . $shift;
				}
				if ($newPLFConstraint){
					$bounds .= " y" . $shift . " <= 1 \n";
					$integers .= " y" . $shift; 
				}
			}
						echo "outputting to algorithm";
			// Collect all the strings to generate the input string
			$input = $objective . $constraint . $bounds . $integers . " End";
			
			//TODO: pass input string directly to algorithm, may need to change.
			echo $input;
			echo "end of code";
			return $input;
	}
	
	
?>