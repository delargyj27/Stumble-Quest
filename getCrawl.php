<?php

include('db.php');

//Select all columns from bars table
$sql = "SELECT * FROM crawls WHERE crawl_id = ?";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET["id"]]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Send data to front end - problem with this line here
    echo json_encode($data[0], JSON_THROW_ON_ERROR ); 
} catch (Exception $e) {
    echo $e;
    die($e);
}


?>