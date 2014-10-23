<?php include 'PHP/functions.php'; ?>
<?php require_once "PHP/SqlObject.php"; ?>
<?php require 'PHP/uac.php'; ?>
<?php
    $pageTitle = "Your Specialisations";
    $UserAccessControl = new UserAccessControl(); 
    if (!$UserAccessControl->isUserLoggedIn() == true) {
        header("Location: Global_Gateway.php");
    } 
    $UserAccessControl->checkTimeout();

        $sqlObject = new \PHP\SqlObject("select * from facilitator_specialisations 
right join specialisations on facilitator_specialisations.spec_id=specialisations.spec_id
where user_id = :userid or user_id is null order by 'spec_id'", array($_SESSION['user_id']));
        $data = $sqlObject->Execute();

if($_POST) {
    var_dump($_POST);
    $sqlObject = new \PHP\SqlObject("DELETE FROM facilitator_specialisations WHERE user_id = :userid", array($_SESSION['user_id']));
    $sqlObject->Execute();

        $query = "INSERT INTO facilitator_specialisations (user_id,spec_id)VALUES";
    foreach ($_POST as $key => $value) {
        $key = trim($key, "chk_");
        $query .= "('".$_SESSION['user_id']."','".$key."'),";
        header("Location: Volunteer_Specialisations.php");
    }
    $query = substr($query, 0, -1);
    echo $query;
    $sqlObject2 = new \PHP\SqlObject($query);
    $sqlObject2->Execute();
    
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'Include/Global_Head.inc'; ?>
    <link href="CSS/SideBar.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/LocationSeparator.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/Timetable.CSS" rel="stylesheet" type="text/css">
    <link href="CSS/TimetableInteraction.CSS" rel="stylesheet" type="text/css">
    <script src = "js/filters.js"></script>
    <script src = "js/TimetableInteraction.js"></script>
</head>
<body>
<main>
    <?php include 'Include/Global_Page_Head.inc'; ?>
    <?php
    $currentPlace = "<a href = \"index.php\">
                    <img src = \"IMG/dashboard.png\" alt = \"dashboard\" class = \"inline-image\">
                        <p>Home</p>
                    </a>
                    <p> > Timetable</p>";
        include 'Include/Global_Breadcrumb.inc'; ?>
    <?php include 'Include/Global_Sidebar.inc'; ?>
    <div class="push-right pageWrapper">
    <div class="contentWrapper">
        <div class="headElement">
            <img src="IMG/calander.png" alt="Calander" class="inline-image">
            <h2 class="inline-text">Your Specialisations</h2>
        </div>
        <span class="">
        <?php


        echo "<form action='Volunteer_Specialisations.php' method='post'>";
        foreach ($data as $spec) {
            $chkboxhtml = "<div class='checkboxWrapper'><input type='checkbox'";
            if($spec['user_id']){ 
                $chkboxhtml .= " checked";
            }
            $chkboxhtml .= " id=".$spec['spec_id']." name='chk_".$spec['spec_id']."' value='".$spec['spec_name']."'><label for='".
            $spec['spec_id']."'>".$spec['spec_name']."</label></div>";
            echo $chkboxhtml;
        }
        echo "<input type='submit' value='Save Specialisations' /></form>";
        ?>
        </span>
    </div>
</main>
</body>
</html>