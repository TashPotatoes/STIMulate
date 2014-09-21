<!DOCTYPE HTML>
<html>
	<head>
		<title>Timetable</title>
		<!--<link rel="stylesheet" type="text/css" href="CSS/InputTimetable.css"> -->
		<link rel="stylesheet" type="text/css" href="CSS/Timetable.css">
	</head>
	<body>
		<!-- Main Menu Code (move this later) -->
		<div class="timetableWrapper">
			<div>
			<table>
				<tr>
					<th colspan="2">Monday</th>
					<th colspan="2">Tuesday</th>
					<th colspan="2">Wednesday</th>
					<th colspan="2">Thursday</th>
					<th colspan="2">Friday</th>
				</tr>
				<!-- green is 3, yellow is 2, red is 1, white is null -->
				<tr>
					<td class="time">9-10</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">9-10</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">9-10</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">9-10</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">9-10</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">10-11</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">10-11</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">10-11</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">10-11</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">10-11</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">11-12</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">11-12</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">11-12</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">11-12</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">11-12</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">12-1</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">12-1</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">12-1</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">12-1</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">12-1</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">1-2</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">1-2</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">1-2</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">1-2</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">1-2</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">2-3</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">2-3</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">2-3</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">2-3</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">2-3</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">3-4</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">3-4</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">3-4</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">3-4</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">3-4</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>
				<tr>
					<td class="time">4-5</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">4-5</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">4-5</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">4-5</td>
					<td onclick="clickColorEvent(this)"></td>
					<td class="time">4-5</td>
					<td onclick="clickColorEvent(this)"></td>
				</tr>	
			</table>



			<!-- code to change colours -->
			<script type="text/javascript">
				var colors = ["green", "yellow", "red", "white"];
				function clickColorEvent(obj){
					obj.colorIndex = obj.colorIndex || 0;
					obj.style.backgroundColor = colors[obj.colorIndex++ % colors.length];
					console.log(obj.style.backgroundColor);
				}
			</script>

			</div>

		</div>

	</body>
</html>