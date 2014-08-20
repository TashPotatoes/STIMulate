<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
require_once ('PHP/Beautifier.php');
$script = $_GET["script"];
if (!$script) {
	echo "<BR><B>ERROR: Script Name needed</B><BR>";
	exit(1);
} else {
	//if (ereg("(\.php|\.inc|\.c)$",$script)) {
	// Create the instance
	$oBeautifier = new PHP_Beautifier();
	// Add a filter, without any parameter
	//$oBeautifier->addFilter('ArraySimple');
	// Add another filter, with one parameter
	$oBeautifier->addFilter('Pear', array('add_header' => 'php'));
	// Set the indent char, number of chars to indent and newline char
	$oBeautifier->setIndentChar("\t");
	$oBeautifier->setIndentNumber(1);
	$oBeautifier->setNewLine("\n");
	// Define the input file
	$oBeautifier->setInputFile($script);
	// Define an output file.
	$oBeautifier->setOutputFile($script . '.beautified.php');
	// Process the file. DON'T FORGET TO USE IT
	$oBeautifier->process();
	// Show the file (echo to screen)
	echo "<!DOCTYPE public \"-//w3c//dtd html 4.01 transitional//en\"\n" . " \"http://www.w3.org/TR/html4/loose.dtd\">\n" . "<html>\n" . "  <head>\n" . "    <title>Sorgente di \"" . $_GET["script"] . "\"</title>\n" . "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n" . "  </head>\n" . "  <body>\n";
	$oBeautifier->show();
	echo "\n  </body>\n</html>\n";
	// Save the file
	$oBeautifier->save();
	//} else {
	//	echo "<H1>ERROR: Only PHP or include script names are allowed</H1>";
	//}
}
?>
