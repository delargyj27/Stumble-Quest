<?php

try {
    include('db.php');

    // Get the request body
    $body = json_decode(file_get_contents("php://input"));

    //Select from bars table
    $sql = "
        INSERT INTO 
            crawlbars(crawl_id, barid, visitorder) 
        VALUES
            (
                ?, 
                ?, 
                IFNULL(
                    (
                        SELECT (visitorder + 1) 
                        FROM crawlbars AS CB
                        WHERE crawl_id = ?
                        ORDER BY visitorder DESC
                        LIMIT 1
                    ),
                    1
                )
            )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$body->crawlId, $body->barId, $body->crawlId]);

    //Send data to front end - problem with this line here
    echo json_encode(["message" => "Bar added to crawl successfully!"], JSON_THROW_ON_ERROR);
} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
}
