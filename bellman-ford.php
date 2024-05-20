<?php
header('Content-Type: application/json');

// Graph array with distances
$graph = [
    'KLIA' => ['Putrajaya' => 30],
    'Putrajaya' => ['KLIA' => 30, 'Berjaya Times Square' => 30, 'Merdeka 118' => 15, 'Petronas Twin Towers' => 20],
    'Berjaya Times Square' => ['Putrajaya' => 30, 'Petronas Twin Towers' => 20, 'Bukit Bintang' => 15],
    'Merdeka 118' => ['Putrajaya' => 15, 'Bukit Bintang' => 10],
    'Petronas Twin Towers' => ['Putrajaya' => 20, 'Berjaya Times Square' => 20, 'Merdeka Square' => 20],
    'Merdeka Square' => ['Petronas Twin Towers' => 20, 'Bukit Bintang' => 5],
    'Bukit Bintang' => ['Berjaya Times Square' => 15, 'Merdeka 118' => 10, 'Merdeka Square' => 5, 'KL Tower' => 15, 'Exchange 106 @ TRX' => 5],
    'KL Tower' => ['Bukit Bintang' => 15, 'Tabung Haji Tower' => 30],
    'Exchange 106 @ TRX' => ['Bukit Bintang' => 5, 'Tabung Haji Tower' => 20],
    'Tabung Haji Tower' => ['Exchange 106 @ TRX' => 20, 'KL Tower' => 30]
];

function initializeGraph($graph) {
    $edges = [];
    foreach ($graph as $source => $neighbors) {
        foreach ($neighbors as $destination => $distance) {
            $edges[] = [$source, $destination, $distance];
        }
    }
    return $edges;
}

function bellmanFord($graph, $source, $target, &$steps) {
    $edges = initializeGraph($graph);
    $dist = [];
    $prev = [];

    foreach ($graph as $vertex => $neighbors) {
        $dist[$vertex] = INF;
        $prev[$vertex] = null;
        $steps++; // Initialization step
    }

    $dist[$source] = 0;
    $steps++; // Initialization step

    $vertices = array_keys($graph);

    for ($i = 0; $i < count($vertices) - 1; $i++) {
        foreach ($edges as $edge) {
            list($u, $v, $weight) = $edge;
            $steps++; // Edge relaxation step
            if ($dist[$u] != INF && $dist[$u] + $weight < $dist[$v]) {
                $dist[$v] = $dist[$u] + $weight;
                $prev[$v] = $u;
                $steps++; // Update step
            }
        }
    }

    // Check for negative weight cycles
    foreach ($edges as $edge) {
        list($u, $v, $weight) = $edge;
        $steps++; // Cycle check step
        if ($dist[$u] != INF && $dist[$u] + $weight < $dist[$v]) {
            throw new Exception("Graph contains a negative-weight cycle");
        }
    }

    // Build the path
    $path = [];
    $u = $target;
    while (isset($prev[$u])) {
        array_unshift($path, $u);
        $u = $prev[$u];
        $steps++; // Path construction step
    }
    array_unshift($path, $source);
    $steps++; // Final step

    return ['distance' => $dist[$target], 'path' => $path];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $source = 'KLIA';
    $target = $_POST['destination'];
    $steps = 0;

    $start_time = microtime(true); // Start time
    $result = bellmanFord($graph, $source, $target, $steps);
    $end_time = microtime(true); // End time

    $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds

    $result['steps'] = 'O(VE)';
    $result['big_o'] = 'O(VE)';
    $result['best_case'] = 'O(VE)';
    $result['average_case'] = 'O(VE)';
    $result['worst_case'] = 'O(VE)';
    $result['execution_time'] = $execution_time; // Add execution time

    echo json_encode($result);
}
?>
