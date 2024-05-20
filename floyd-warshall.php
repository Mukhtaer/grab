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

function initializeFloydWarshallGraph($graph) {
    $dist = [];
    $next = [];
    $nodes = array_keys($graph);

    foreach ($nodes as $i) {
        foreach ($nodes as $j) {
            if ($i == $j) {
                $dist[$i][$j] = 0;
            } elseif (isset($graph[$i][$j])) {
                $dist[$i][$j] = $graph[$i][$j];
            } else {
                $dist[$i][$j] = INF;
            }
            $next[$i][$j] = $j;
        }
    }

    return [$dist, $next];
}

function floydWarshall($graph, $source, $target, &$steps) {
    list($dist, $next) = initializeFloydWarshallGraph($graph);

    $nodes = array_keys($graph);
    foreach ($nodes as $k) {
        foreach ($nodes as $i) {
            foreach ($nodes as $j) {
                $steps++; // Step count
                if ($dist[$i][$j] > $dist[$i][$k] + $dist[$k][$j]) {
                    $dist[$i][$j] = $dist[$i][$k] + $dist[$k][$j];
                    $next[$i][$j] = $next[$i][$k];
                }
            }
        }
    }

    // Reconstruct the path
    $path = [];
    if ($next[$source][$target] == null) {
        return ['distance' => INF, 'path' => $path]; // No path
    }

    $u = $source;
    while ($u != $target) {
        $path[] = $u;
        $u = $next[$u][$target];
    }
    $path[] = $target;

    return ['distance' => $dist[$source][$target], 'path' => $path];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $source = 'KLIA';
    $target = $_POST['destination'];
    $steps = 0;

    $start_time = microtime(true); // Start time
    $result = floydWarshall($graph, $source, $target, $steps);
    $end_time = microtime(true); // End time

    $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds

    $result['steps'] = 'O(V^3)';
    $result['big_o'] = 'O(V^3)';
    $result['best_case'] = 'O(V^3)';
    $result['average_case'] = 'O(V^3)';
    $result['worst_case'] = 'O(V^3)';
    $result['execution_time'] = $execution_time; // Add execution time

    echo json_encode($result);
}
?>
