<?php
    // Connect to mongo database

	// Configuration
	$dbhost = 'localhost';
	$dbport = '27017';

	$conn = new MongoDB\Driver\Manager("mongodb://$dbhost:$dbport");

    echo "<br>Connected to MongoDB<br>";
  
	print_r($conn);




?>


?>