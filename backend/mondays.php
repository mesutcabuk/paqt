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
 * Get all Mondays of complete weeks within a given date range.
 *
 * @param string $startDate Start date in YYYY-MM-DD format
 * @param string $endDate End date in YYYY-MM-DD format
 * @return array List of Mondays within complete weeks
 */
function getMondaysInRange($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    // end date includes full day
    $end->setTime(23, 59, 59);

    $mondays = [];

    // if it's not already Monday move to the first Monday after startDate
    if ($start->format('N') != 1) {
        $start->modify('next Monday');
    }
    //simple loop
    while ($start <= $end) {
        // Calculate the Sunday of this week
        $sunday = clone $start; // since php7 clone is beter
        $sunday->modify('next Sunday');

        // Only add Monday if the full week is within range
        if ($sunday <= $end) {
            $mondays[] = $start->format('Y-m-d');
        }

        // Move to the next week
        $start->modify('+1 week');
    }

    return $mondays;
}

// another simple platform check, I'll use only here to give an idea..
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $startDate = "2024-03-05";
    $endDate = "2024-03-31";
    $mondays = getMondaysInRange($startDate, $endDate);
    echo "Mondays in full weeks: " . implode(", ", $mondays) . PHP_EOL;
}
?>
