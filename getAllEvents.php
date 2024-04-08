<?php
header('Content-Type: application/json; charset=utf-8');
include('db.php');

$sql = "SELECT * FROM events";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Creates data that Mapbox will parse, read, & encode as GeoJSON data
    $returnData = [];
    foreach ($data as $key => $eventData) {
        $returnData[] = [
            'type' => 'Feature'.$eventData['id'],
            'properties' => [
                'description' => '<strong>'.$eventData['name'].'</strong><p>'.$eventData['description'].'</p>',
                'icon' => 'rocket-15'
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [$eventData['lat'], $eventData['lng']]
            ]
        ];
    }
} catch (Exception $e) {
    die($e);
}

echo json_encode($returnData);
?>