<?php
/**
 * Created by PhpStorm.
 * User: Hayden
 * Date: 16/09/14
 * Time: 7:09 PM
 */
session_start();
echo $_SESSION['facilitator_id'];
?>

<!DOCTYPE html>

<html>
    <head>
        <?php include 'Include/GlobalHead.inc'; ?>
        <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
        <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    </head>
    <body>
        <main>
            <?php include 'Include/Header.inc'; ?>
            <?php
                $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>";
                include 'Include/LocationSeparator.inc'; ?>
            <?php include 'Include/SideBar.inc'; ?>


        </main>
    </body>
</html>