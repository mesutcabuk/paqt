<?php
require 'mondays_in_range.php';

function testGetMondaysInRange() {
    $tests = [
        [
            'start' => '2024-03-05',
            'end' => '2024-03-31',
            'expected' => ['2024-03-11', '2024-03-18', '2024-03-25']
        ],
        [
            'start' => '2024-02-01',
            'end' => '2024-02-29',
            'expected' => ['2024-02-05', '2024-02-12', '2024-02-19', '2024-02-26']
        ],
        [
            'start' => '2024-04-01',
            'end' => '2024-04-10',
            'expected' => []
        ],
        [
            'start' => '2024-06-10',
            'end' => '2024-07-07',
            'expected' => ['2024-06-17', '2024-06-24']
        ],
    ];

    foreach ($tests as $index => $test) {
        $result = getMondaysInRange($test['start'], $test['end']);
        $passed = ($result === $test['expected']) ? "✅ Passed" : "❌ Failed";
        echo "Test " . ($index + 1) . ": $passed\n";
        if ($result !== $test['expected']) {
            echo "Expected: " . implode(", ", $test['expected']) . "\n";
            echo "Got: " . implode(", ", $result) . "\n";
        }
    }
}

testGetMondaysInRange();
?>
