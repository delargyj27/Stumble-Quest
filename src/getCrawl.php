<?php

include('db.php');

try {
    //Select all columns from crawls table
    $sql = "
        SELECT * FROM
            crawls 
        WHERE crawl_id = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET["id"]]);
    $crawlData = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Select all columns from crawlbars table
    $sql = "
        SELECT * FROM
            crawlbars 
        WHERE crawl_id = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET["id"]]);
    $crawlBarsData = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Send data to front end 
    echo json_encode([
        "crawl" => $crawlData[0],
        "crawlBars" => $crawlBarsData
    ], JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    echo $e;
    die($e);
}
