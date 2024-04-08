<?php
include('db.php');



if(isset($_POST['name'])){
    $sql = "INSERT into events (name,description,lat,lng) VALUES (:name,:description,:lat,:lng)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name'=>$_POST['name'],
            'description'=>$_POST['description'],
            'lat'=>$_POST['lat'],
            'lng'=>$_POST['lng'],
        ]);

        echo json_encode('success');
    
    } catch (Exception $e) {
        die($e);
    }
}