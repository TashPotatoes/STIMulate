<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!$UserAccessControl->isUserLoggedIn()) {} else {
    switch ($_SESSION['user_type']) {
        case 'staff':
            $user_type = 1;
            break;
        case 'student';
            $user_type = 0;
            break;
    }
    $navigation = array(
        //   ("usertype[staff/student]", "Name", "file", "icon");
        //     0: student 1: staff
        array(0, "Dashboard", "Dashboard.php", "home"),
        array(0, "Your Shifts", "shifts.php", "calander"),
        array(0, "Your Availabilities", "TimetablePref.php", "calander"),
        array(0, "Record a session", "#", "crown"),
        array(0, "Manage Specialisations", "specialisations.php", "crown"),
        array(0, "Record a shift", "#", "crown"),
        array(0, "Record an absence", "RecordAbsentie.php", "crown"),
        array(1, "Manage Facilitators", "ManageStaff.php", "home"),
        array(1, "Manage Shifts", "manageShifts.php", "home"),
        array(1, "Record a session", "#", "crown"),
        array(1, "Record a shift", "#", "crown"),
        array(1, "Manage PLFs", "ManagePLF.php", "crown"),
        array(1, "Manage Staff", "ManageStaff.php", "crown"),
        array(1, "Manage Individual Shifts", "manageShifts.php", "crown"),
        array(1, "Manage Abenties", "ManageAbsentie.php", "crown"));
    ?>

    <aside>
        <ul class='nav'>
            <?php
            foreach ($navigation as $item) {
                if($user_type == (string)$item[0]) {
                    echo "<li class='icon icon-".$item[3]."'><a href='".$item[2]."'>".$item[1]."</a></li>";
                }
            }
            ?>
        </ul>
    </aside>
<?php } ?>