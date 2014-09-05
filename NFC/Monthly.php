<!DOCTYPE html>
<!--[if lt IE 7 ]>              <html class="ie6">      <![endif]-->
<!--[if IE 7 ]>                 <html class="ie7">      <![endif]-->
<!--[if !IE 7]>                 <html class="not-ie7">  <![endif]-->
<!--[if IE 8 ]>                 <html class="ie8">      <![endif]-->
<!--[if IE 9 ]>                 <html class="ie9">      <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->

<html>                  <!--<![endif]-->

<head>

    <title>NFC Card Scanning Prototype</title>
    <link rel="stylesheet" href="CSS/TimeTable.css" type="text/css"/>
    <link rel="stylesheet" href="https://qutvirtual3.qut.edu.au/css/qut/common.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://qutvirtual3.qut.edu.au/css/qut/template/template-v2.css" type="text/css" media="all" />

    <link rel="stylesheet" href="https://qutvirtual3.qut.edu.au/css/qut/js/libs/jquery.ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://qutvirtual3.qut.edu.au/css/qut/js/libs/bootstrap.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://qutvirtual3.qut.edu.au/css/qut/js/plugins/jquery-plugins.css" type="text/css" media="all" />




    <link href="https://qutvirtual3.qut.edu.au/images/qut/template/favicon.ico" rel="Shortcut Icon" />


    <script src="JS/jQuery.js" type="text/javascript"></script>
    <script src="JS/Absenties.js" type="text/javascript"></script>
    <script src="JS/TextSuggestion.js" type="text/javascript"></script>

</head>

<?php include("new/header.php"); ?>

    <style>
        .left {
            width:25%;
            margin-right:1%;
            float: left;
        }
        .right {
            width:74%;
            float: right;
        }

    </style>
    <!-- Start Page Body -->
    <div id="wrapper">
        <div id="main-wrapper">
        </div>

        <div id="background-wrapper">

            <div id="page-functions-top">

                <div id="breadcrumb">
                    <p class="hide">You are here:</p>
                    <ul>
                        <li class="home">
                            <a title="Go to homepage" href="https://qutvirtual3.qut.edu.au">
                                <span class="hide">Homepage</span>
                            </a>
                        </li>

                        <li>Monthly Timetable</li>
                    </ul>
                </div>
                <div id="help-print-page"><ul>
                        <li class="page-help"><a href="stim_session_p.help" class="popup"><span class="hide">Help</span></a></li>
                    </ul></div>
            </div>
            <div id="col-wrapper" class="right-col-Wide clear-both"><div id="content">
                    <h1>STIMulate Reporting</h1>

                    <div class="left">

                        <fieldset><legend>Menu</legend>
                            <ul class="unstyled">
                            </ul>
                        </fieldset>

                    </div>

                    <div class="right">

                        <?php include 'Include/TimeTable.inc'; ?>


                    </div>

                </div>




                <div id="page-functions-bottom">


                    <div id="breadcrumb">
                        <p class="hide">You are here:</p>
                        <ul>
                            <li class="home">
                                <a title="Go to homepage" href="https://qutvirtual3.qut.edu.au">
                                    <span class="hide">Homepage</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>



            </div>
        </div>
        <!-- End Page Body -->

    </div>



<?php include('new/footer.php'); ?>