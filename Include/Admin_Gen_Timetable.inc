<?php
/**
 * Created by PhpStorm.
 * User: Natasha Moffat
 * Date: 19/10/2014
 * Time: 3:10 PM
 */


//Requiring SQL Object
require_once 'PHP/SqlObject.php';
require_once 'Include/Admin_Gen_Timetable_Tools.inc';
$modelOPL = "";
?>

<div class = "push-right">
	
	<div class = "headElement">
         <img src="IMG/calander.png" alt="Calander" class = "inline-image">
         <h2 class = "inline-text">Generate Table</h2>
     </div>
	 
	 <div class = "contentWrapper">
		<div class = "content">
	 
<?php
			// if the stream has been selected, generate the OPL
			if(isset($_POST['stream'])) {

				$stream =  $_POST['stream'];
				//echo ("<p> Current Stream: $stream </p>");
				$modelOPL = GenerateOPL($stream);
				echo "<textarea id='source' cols='100' rows='10'>";
				
				// if the OPL has been generated then display it, with option to run algorithm
				if ($modelOPL != "") {
					
					echo ($modelOPL); 
					echo ("</textarea>");
					
					echo ("<input type='submit' value='Generate Table' onclick='run() '/> "); 
					echo " checking if is posted";
					
					if (isset($_POST['variable'])){
						echo "posted variable is set";
						ResultsToDatabase();
						echo "<p>PLF happiness index for this table is <pre id=\"log\"/></p>";
					}
				} else {
					// if stream is set but no OPL generated 
					echo "<textarea id='source' cols='100' rows='10'>";
				
					echo ("OPL loading");
					echo ("</textarea>");
				}
				
				
				
			} else {
				// if the stream has not been selected, display options to select
			?>	

				<div class="stream"">
					Select stream
					
					<form name="streamSelect" action="Admin_Gen_Timetable.php" method="post">
					<ul>
						<li><input type="radio" name="stream" id="stream-it"value="it"><label for="stream-it">IT</label><br/></li>
						<li><input type="radio" name="stream" id="stream-ma"value="ma"><label for="stream-ma">Maths</label><br/></li>
						<li><input type="radio" name="stream" id="stream-sc"value="sc"><label for="stream-sc">Science</label><br/></li>
						<li><input type="radio" name="stream" id="stream-dh"value="dh"><label for="stream-dh">Duty Host</label><br/></li>
					</ul>
					<input type="submit" value="Select Stream">		
					</form>
					
				</div>

<?php	
			} 	
			echo "<pre id=\"log\"/></p>";	

?>

<script>
	var start;
	var logNode = document.getElementById("log");
	var log = glp_print_func = function(value){
		var now = new Date();
		var d = (now.getTime() - start.getTime()) / 1000;
		logNode.appendChild(document.createTextNode(value + "\n"));
		if (d > 200) throw new Error("timeout");
		console.log(value);
	};
</script>


		</div>
	</div>
</div> 
