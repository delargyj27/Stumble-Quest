<?php

try {
    include('db.php');

    // Get the request body
    $body = json_decode(file_get_contents("php://input"));

    //Select from bars table
    $sql = "
      DELETE FROM crawlbars WHERE crawl_id=? 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$body->crawlId]);

    //Send data to front end - problem with this line here
    echo json_encode(["message" => "Crawl cleared successfully!"], JSON_THROW_ON_ERROR);
} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
}
