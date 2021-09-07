<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration
$dbhost = 'localhost';
$dbport = '27017';
$dbname = 'sampledb';
$dbusername = 'root';
$dbpassword = 'root.pwd';


echo "<br>check extension loaded or not <br>";
echo extension_loaded("mongodb") ? "loaded\n" : "not loaded\n";

$uri = 'mongodb://'. $dbusername . ':' . $dbpassword .'@'. $dbhost . ':'. $dbport . '/' . $dbname;
echo "<br>url =". $uri ."<br>";

echo "<br>Connect mongodb<br>";
$mongoClient = new MongoDB\Driver\Manager($uri);
print_r($mongoClient);


echo "<br>List collections<br>";

$filter = [];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongoClient->executeQuery('sampledb.bios', $query); // $mongo contains the connection object to MongoDB
foreach($rows as $r){
   print_r($r);
   echo '<br>';
}





echo "<br>Executed<br>";    



?>