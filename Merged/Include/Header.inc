<?php

if (session_status() == PHP_SESSION_NONE) {
session_start();
}
?>
<header>
    <div id = "logo">
        <img src="IMG/QUT.png" alt="QUT Logo" class = "inline-image">
        <h1 class = inline-text>STIMulate</h1>
    </div>
    <?php    //echo $UserAccessControl->isUserLoggedIn(); ?>
    <?php if($UserAccessControl->isUserLoggedIn()){ ?>
    <div id = "user-information">
        <ul>
            <li id = "latest-information">
                <img src="IMG/latest.png" alt="latest information picture" class = "inline-image">
                <p class = "notificationNumber inline-text">2</p>
            </li>
            <li id = "loginButton">
                <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<img src="IMG/student.png" alt = "student Picture" class = "inline-image">';
                    echo '<p id = "studentName" class = "inline-text">'.$_SESSION['user_id'].'</p>';
                } else { echo "fek.";
                }
                ?>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <?php } ?>
</header>