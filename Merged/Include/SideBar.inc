<?php
    $navigation = array 
        (
            array("Dashboard", "index.php", "home"),
            array("Your Shifts", "timetable.php", "calander"),
            array("Your Availabilities", "TimetablePref.php", "calander"),
            array("Record a session", "#", "crown"),
            array("Record a shift", "#", "crown")
        );


?>
<aside>
<ul class='nav'>
<?php
foreach ($navigation as $item) {
    echo "<li class='icon icon-".$item[2]."'><a href='".$item[1]."'>".$item[0]."</a></li>";
}
?>
</ul>
</aside>
