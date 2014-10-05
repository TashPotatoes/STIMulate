<?php
    $navigation = array 
        (
            array("Dashboard", "Dashboard.php", "home"),
            array("Timetable", "shifts.php", "calander alternative"),
            array("Your Preferences", "TimetablePref.php", "calander"),
            array("Record a session", "#", "crown alternative"),
            array("Record a shift", "#", "crown"),
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
