<?php

/**
 * FizzBuzz Implementation
 * 
 * This script prints numbers from a given range, replacing:
 * - Multiples of 3 with "Fizz"
 * - Multiples of 5 with "Buzz"
 * - Multiples of both 3 and 5 with "FizzBuzz"
 * 
 * Usage:
 * php script.php <start> <end>
 * * start doesn't need to be smaller numer
 * * negative numbers allowed
 * * keep it simple, didn't want to make it complicated with big numbers
 * 
 * @author Mesut Cabuk
 * @email mesutcabuk@gmail.com
 * @version 1.0
 */

/**
 * Prints FizzBuzz sequence for a given range.
 * 
 * @param int $start The starting number of the range.
 * @param int $end The ending number of the range.
 */
function fizzBuzz($start, $end) {
    // Loop through the range and apply FizzBuzz logic
    for ($i = $start; $i <= $end; $i++) {
        if ($i % 3 === 0 && $i % 5 === 0) {
            echo "FizzBuzz\n";
        } elseif ($i % 3 === 0) {
            echo "Fizz\n";
        } elseif ($i % 5 === 0) {
            echo "Buzz\n";
        } else {
            echo "$i\n";
        }
    }
}

// Validate command line arguments
if ($argc !== 3 || !is_numeric($argv[1]) || !is_numeric($argv[2])) {
    echo "Error: Please provide two valid numeric arguments.\n";
    echo "Usage: php script.php <start> <end>\n";
    exit(1);
}

// Convert arguments to integers, 
// redundant on purpose, didn't assign it before to keep all error check in one place 
$start = (int)$argv[1];
$end = (int)$argv[2];

// Ensure the range is in correct order
if ($start > $end) {
    [$start, $end] = [$end, $start]; 
}

// Execute the FizzBuzz function
fizzBuzz($start, $end);