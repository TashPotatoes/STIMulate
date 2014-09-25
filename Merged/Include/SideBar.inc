<aside>
<?php
    $navigation = array 
        (
            array("Dashboard", "index.php", "IMG/dashboard.png"),
            array("Your Shifts", "timetable.php", "IMG/calander.png"),
            array("Your Availabilities", "TimetablePref.php", "IMG/plf.png"),
            array("Record a session", "#", "IMG/studentsign.png"),
            array("Record a shift", "#", "IMG/plf.png")
        );


?>

<ul class='nav'>
<?php
foreach ($navigation as $item) {
    echo "<li class='icon-calander'><a href='".$item[1]."'><img src = ".$item[2]." alt = \"sidebar pic\" class = \"inline-image side-img\"><p  class = \"inline-text\">".$item[0]."</p></a></li>";
}
?>
</ul>
</aside>

