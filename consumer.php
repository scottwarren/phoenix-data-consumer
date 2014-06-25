<?php

// ini_set('display_errors', '1');
ini_set('display_errors', 1);

$phoenixFile = fopen('example-data.txt', 'r');

$phoenixData = array();

// make sure it exists (and we have access)
if ($phoenixFile) {
    while (!feof($phoenixFile)) {
        $line = fgets($phoenixFile);
        $line = json_decode($line);

        if ($line === null) {
            break;
        }

        // get the search type from the data file
        $searchType = $line->test;

        // if the test doesn't exist in our data array, add a new key
        if (!array_key_exists($searchType, $phoenixData)) {
            $phoenixData[$searchType] = array();

            // set the error count for this search type to be 0;
            $phoenixData[$searchType]['errorCount'] = 0;
        }

        // if this iteration is an error, we save different data
        if (array_key_exists('error', $line)) {
            $phoenixData[$searchType]['errorCount']++;
        } else {
            // if duration is set already add this iterations duration and average them
            // otherwise duration is now this iterations duration
            $phoenixData[$searchType]['averageDuration'] = isset($phoenixData[$searchType]['averageDuration']) ? ($phoenixData[$searchType]['averageDuration'] + $line->duration) / 2 : $line->duration;
        }



    }
} else {
    // error opening file
    die('there was an error loading the input file');
}
header("Content-Type:application/json");
print_r(json_encode($phoenixData));
fclose($phoenixFile);
