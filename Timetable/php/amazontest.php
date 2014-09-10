<code>
<?php
echo "- START";
$user = "stimdev";
$pass = "itisbest";

$rand = substr(md5(microtime()),rand(0,26),5);
try
{
$conn = new PDO('mysql:host=stimulate.ceu1tvrd8kag.ap-southeast-2.rds.amazonaws.com;dbname=stimulate', $user, $pass);
  echo "<br/>- Connected<p>";
}
catch (Exception $e)
{
  echo "Unable to connect: " . $e->getMessage() ."<p>";
}
$num = $rand;
$query = $conn->prepare("INSERT INTO STIMulate.connection_test (`test_code`) VALUES (:code)");
$query->bindParam(':code', $num);  
$query->execute();

//$q2 = $conn->prepare("SELECT test_code FROM connection_test");
$q2 = $conn->prepare("SELECT * FROM STIMulate.connection_test");
$q2->execute();
$dis = $q2->fetchAll();
$dis = array_map('reset', $dis);
print_r($dis);
?>
</code>

