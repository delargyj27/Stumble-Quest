<?php
header('Content-Type: application/json; charset=utf-8');
include('db.php');

$sql = "SELECT * FROM events";
//$sql = "SELECT eventname, eventdate, start_time, end_time FROM events WHERE barid = ?";

/*
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
*/

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); //data resulting from that query

    //Send data to front end - problem with this line here
    echo json_encode($data, JSON_THROW_ON_ERROR ); 
} catch (Exception $e) {
    echo $e;
    die($e);
}

?>