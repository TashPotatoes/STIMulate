<!DOCTYPE html>
<html>
<head>
    <title>Timetable Prototype</title>
    <link href="CSS/Global.css" type="text/css" rel = "stylesheet">
    <link href="CSS/Header.css" type="text/css" rel = "stylesheet">
    <link href="CSS/TimeTable.css" type="text/css" rel = "stylesheet">
    <link href="CSS/Monthly.css" type="text/css" rel = "stylesheet">
    <script src="JS/jQuery.js" type="text/javascript"></script>
    <script src="JS/Absenties.js" type="text/javascript"></script>
</head>
<body>
<header>
    <h1>Stimulate Rapid Prototype [V19/08/2014]</h1>
    <ul>
        <li><a href = "..\1.0 Stimulate\Index.php">Record a Session</a></li>
        <li><a href = "../Index.php">Monthly Calendar</a></li>
        <li><a href = "Weekly.php">Weekly Calendar</a></li>
    </ul>
</header>
<div id="wrapper">
    <div id = "searchBarWrapper">
        <form id = "search" method = "GET" action = "search.php">
            <fieldset>
                <legend>Search Stimulate</legend>
                <div id = "searchBar">
                    <input type = "text" name = "searchvalue" class = "inputField" placeholder = "Student Name, UnitCode, Specialisation..." autocomplete="off">
                    <div id = "suggestions"></div>
                </div>
                <input type = "submit" value = "Search" class = "submitBtn">
            </fieldset>
        </form>
    </div>
    <div id = "calWrapper">
        <?php include "Include/Weekly.inc" ?>
    </div>
</div>

</body>
</html>
