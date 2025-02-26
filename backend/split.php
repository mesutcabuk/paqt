<?php

/**
 * Splitting Arrays  Implementation
 * 
 * This script takes a list of numbers and an integer, then splits the list into sublists of the specified size:
 * 
 * @author Mesut Cabuk
 * @email mesutcabuk@gmail.com
 * @version 1.0
 */

/**
 * Splits an array into smaller sub-arrays, each containing at most a given number of elements.
 *
 * @param array $list The input array of numbers to be split.
 * @param int $size The maximum number of elements in each sub-array.
 * @return array Returns an array of sub-arrays, each with at most $size elements.
 * @throws InvalidArgumentException If the input arguments are invalid.
 */
function splitCollection(array $list, int $size): array {
    // Validate that the array is not empty
    if (empty($list)) {
        throw new InvalidArgumentException("The input list cannot be empty.");
    }

    // Validate that size is a positive integer
    if ($size <= 0) {
        throw new InvalidArgumentException("The size parameter must be a positive integer.");
    }

    $result = [];  // Final array 
    $chunk = [];   // Temp sub-array

    // Iterate over each element in the input list
    foreach ($list as $item) {
        // Ensure that each element is a number
        if (!is_numeric($item)) {
            throw new InvalidArgumentException("All elements in the list must be numeric.");
        }

        $chunk[] = $item;

        // If the chunk reaches the specified size, add it to the result and reset
        if (count($chunk) === $size) {
            $result[] = $chunk;
            $chunk = []; // Reset the chunk array
        }
    }

    // Add the last remaining chunk if it's not empty
    if (!empty($chunk)) {
        $result[] = $chunk;
    }

    return $result;
}

// Example usage with error handling
try {
    $list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,1000];  // Input list of numbers
    $size = 10;  // Maximum size of each sublist

    $list = [1, 2, 3, 1, 2, 3, 1, 2, 3, 1, 2, 3, 1, 2, 3, 1, 2, 3];  // Input list of numbers
    $size = 10;  // Maximum size of each sublist


    $result = splitCollection($list, $size); // Call function

    // Print the formatted result
    echo "Split Collection Result:\n";
    print_r($result);
} catch (InvalidArgumentException $e) {
    // Handle any errors that occur during function execution
    echo "Error: " . $e->getMessage();
}
?>
