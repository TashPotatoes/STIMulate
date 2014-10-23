<!DOCTYPE html>
<html>
<head>
    <?php include 'Include/Global_Head.inc'; ?>
        <?php include 'PHP/functions.php'; ?>
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimeTableFilter.CSS" rel="stylesheet" type="text/css">
</head>
<body>
    <main>
        <div class="index-container">
        <div class="messagebox" id="defaultLogo">
        <img src="IMG/QUT.png" vertical-aign="middle" alt="QUT Logo" class = "inline-image">
        <h1 class = inline-text>About STIMulate</h1>
        </div>
        <?php include 'Include/Global_Timetable.inc'; ?>
        <div class="messagebox" >
            <p>
            <a href="about.php">About STIMulate</a>
            <a href="Volunteer_Shifts.php">Facilitator Login</a>
            </p>
        </div>
        </div>
    </main>
    </body>
</html>