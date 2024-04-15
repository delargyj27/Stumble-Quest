<?php

// $db = "if0_36069118_dbsquest";
// $host = "localhost";
// $user = 'jkmapdev1';
// $pass = 'proximity';

// $db = "if0_36069118_dbsquest";
// $host = "sql105.infinityfree.com";
// $user = 'if0_36069118';
// $pass = '44WqSXc31wzj7';

$host = "localhost";

if($_SERVER["REMOTE_ADDR"] == "::1") {
    $db = "if0_36069118_dbsquest";
    $host = "localhost";
    $user = 'jkmapdev1';
    $pass = 'proximity';    
}
else {
    $db = "if0_36069118_dbsquest";
    $host = "sql105.infinityfree.com";
    $user = 'if0_36069118';
    $pass = '44WqSXc31wzj7';    
}

//PDO Connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

function p($arr){
    echo "<pre>";
        print_r($arr);
    echo "</pre>";
}
?>