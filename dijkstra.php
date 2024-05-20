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

function dijkstra($graph, $source, $target, &$steps) {
    $dist = [];
    $prev = [];
    $queue = new SplPriorityQueue();

    foreach ($graph as $vertex => $neighbors) {
        $dist[$vertex] = INF;
        $prev[$vertex] = null;
        $queue->insert($vertex, INF);
        $steps++; // Initialization step
    }

    $dist[$source] = 0;
    $queue->insert($source, 0);
    $steps++; // Initialization step

    while (!$queue->isEmpty()) {
        $u = $queue->extract();
        $steps++; // Extraction step

        if (!isset($graph[$u])) {
            continue;
        }

        foreach ($graph[$u] as $neighbor => $cost) {
            $alt = $dist[$u] + $cost;
            $steps++; // Calculation step
            if ($alt < $dist[$neighbor]) {
                $dist[$neighbor] = $alt;
                $prev[$neighbor] = $u;
                $queue->insert($neighbor, $alt);
                $steps++; // Insertion step
            }
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
    $result = dijkstra($graph, $source, $target, $steps);
    $end_time = microtime(true); // End time

    $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds

    $result['steps'] = 'O(E + V log V)';
    $result['big_o'] = 'O(E + V log V)';
    $result['best_case'] = 'O(E + V log V)';
    $result['average_case'] = 'O(E + V log V)';
    $result['worst_case'] = 'O(E + V log V)';
    $result['execution_time'] = $execution_time; // Add execution time

    echo json_encode($result);
}
?>
