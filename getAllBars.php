<?php
include('db.php');

//Select all columns from bars table
$sql = "SELECT * FROM bars";
//$sql = "SELECT barname, baraddress, location_latitude, location_longitude FROM bars";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Send data to front end
    echo json_encode($data); 
} catch (Exception $e) {
    die($e);
}


?>