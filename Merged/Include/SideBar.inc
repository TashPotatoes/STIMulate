<?php
    $navigation = array 
        (
            array("Dashboard", "index.php", "IMG/dashboard.png"),
            array("Your Shifts", "timetable.php", "IMG/calander.png"),
            array("Your Availabilities", "TimetablePref.php", ""),
            array("Record a session", "#", "IMG/crown.png"),
            array("Record a shift", "#", "IMG/crown.png")
        );


?>
<aside>
<ul class='nav'>
<?php
foreach ($navigation as $item) {
    echo "<li class='icon-calander'><a href='".$item[1]."'>".$item[0]."</a></li>";
}
?>
</ul>
</aside>
