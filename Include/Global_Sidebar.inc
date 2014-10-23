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
        array(0, "Your Shifts", "Volunteer_Shifts.php", "calander"),
        array(0, "Your Availabilities", "Global_Timetable_Preferences.php", "calander"),
        array(0, "Record a session", "#", "crown"),
        array(0, "Manage Specialisations", "Volunteer_Specialisations.php", "crown"),
        array(0, "Record a shift", "#", "crown"),
        array(0, "Record an absence", "Volunteer_Record_Absent.php", "crown"),
        array(1, "Manage Facilitators", "Admin_Manage.php?action=manageVolunteer", "crown"),
        array(1, "Manage Staff", "Admin_Manage.php?action=manageStaff", "crown"),
        array(1, "Manage Shifts", "Admin_Manage.php?action=manageShift", "crown"),
        array(1, "Manage Abenties", "Admin_Manage.php?action=manageAbsent", "crown"),
        array(1, "Record a session", "#", "crown"),
		array(1, "Generate Timetable", "Admin_Gen_Timetable.php", "crown"),
        );
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