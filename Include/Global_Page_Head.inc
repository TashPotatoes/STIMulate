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
    <?php if($UserAccessControl->isUserLoggedIn()){ ?>
    <div id = "user-information">
        <ul>
            <li id = "loginButton">
                <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<img src="IMG/student.png" alt = "student Picture" class = "inline-image">';
                    echo '<p id = "studentName" class = "inline-text">'.$_SESSION['user_name'].'</p>';
                } else { //echo "fek.";
                }
                ?>
            </li>
            <li><span class = "inline-image no-width"></span><a href="PHP/logout.php">Logout</a></li>
        </ul>
    </div>
    <?php } ?>
</header>

