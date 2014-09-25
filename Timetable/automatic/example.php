<?php

require_once __DIR__ . '/Simplex/Simplex.php';

// require once, code to read from database

$z = new Simplex\Func(array(
	'x1' => 1,
	'x2' => 2,
	'x3' => 10.5,
));

$task = new Simplex\Task($z);

$task->addRestriction(new Simplex\Restriction(array(
	'x1' => 3,
	'x2' => 2,
	'x3' => 5.75,

), Simplex\Restriction::TYPE_LOE, 24));

$task->addRestriction(new Simplex\Restriction(array(
	'x1' => -2,
	'x2' => -4,
	'x3' => -2.3,

), Simplex\Restriction::TYPE_GOE, -32));


$solver = new Simplex\Solver($task);


// require once, code to output to database

echo"<pre>" ;
print_r($solver); 
echo "</pre>";


die();
