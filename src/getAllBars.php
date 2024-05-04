<?php

include('db.php');

//Select all columns from bars table
$sql = "
    SELECT 
        bars.barid, barname, baraddress, location_latitude, location_longitude,
        photourl
    FROM 
        bars INNER JOIN barphotos
        ON( bars.barid = barphotos.barid )
    GROUP BY bars.barid
";
//$sql = "SELECT barname, baraddress, location_latitude, location_longitude FROM bars";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Send data to front end - problem with this line here
    echo json_encode($data, JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    echo $e;
    die($e);
}
