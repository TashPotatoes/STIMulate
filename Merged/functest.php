<?php
	$DEFINE['CARROT'] = 10;
function callme() {
	echo "called<br/>";
	$CARROT += 10;
	echo $CARROT;
}


?>
<html>
<head>

</head>
<body>
<?php
	$i=0;
	while ($i <= 10) {
		echo $i;
		callme();
		$i++;
	}
?>
</body>
</html>