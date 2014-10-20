<?php
/* Importing Classes */
require_once 'PHP/SqlObject.php';

if (session_status() == PHP_SESSION_NONE) {
session_start();
}

?>
<header>
    <div id = "logo">
        <a href="http://qut.edu.au"><img src="IMG/QUT.png" alt="QUT Logo" class = "inline-image"></a>
        <h1 class = inline-text><a href="index.php">STIMulate</a></h1>
    </div>
    <?php    //echo $UserAccessControl->isUserLoggedIn(); ?>
    <?php
    if($UserAccessControl->isUserLoggedIn()){
        $dashBoardCount = RetrieveIntialDataHead();
        ?>
    <div id = "user-information">
        <ul>
            <li id = "latest-information">
                <img src="IMG/latest.png" alt="latest information picture" class = "inline-image">
                <p class = "notificationNumber inline-text"><?php echo $dashBoardCount['absent'][0]['COUNT(*)']?></p>
            </li>
            <li id = "loginButton">
                <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<img src="IMG/student.png" alt = "student Picture" class = "inline-image">';
                    echo '<p id = "studentName" class = "inline-text">'.$_SESSION['user_id'].'</p>';
                } else { //echo "fek.";
                }
                ?>
            </li>
            <li><a href="PHP/logout.php">Logout</a></li>
        </ul>
    </div>
    <?php } ?>
</header>

<?php
/* PAGE SPECIFIC FUNCTIONS */
function RetrieveIntialDataHead(){
    // Sql queries
    $absentieQuery = "SELECT COUNT(*) FROM STIMulate.absences WHERE DATE_ADD(NOW(), INTERVAL 7 DAY) >= absence_timestamp;";

    // Retrieving Absent information
    $sqlObject = new \PHP\SqlObject($absentieQuery);
    $absentInformation = $sqlObject->Execute();


    return array("absent" => $absentInformation);
}