<?php

ini_set('display_errors', 1);
// ini_set('date.timezone', 'Brisbane/Australia');

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
            $phoenixData[$searchType]['lastError'] = $line->datetime;
            // $phoenixData[$searchType]['lastErrorType'] = $line->;
            $phoenixData[$searchType]['lastErrorMessage'] = $line->error;
        } else {
            // if duration is set already add this iterations duration and average them
            // otherwise duration is now this iterations duration
            $phoenixData[$searchType]['averageDuration'] = isset($phoenixData[$searchType]['averageDuration']) ? ($phoenixData[$searchType]['averageDuration'] + $line->duration) / 2 : $line->duration;

            // set origin/departure points in output
            $phoenixData[$searchType]['origin'] = $line->origin;
            $phoenixData[$searchType]['destination'] = $line->destination;

            // set departure/arrival dates in output
            $phoenixData[$searchType]['departDate'] = $line->departDate;
            $phoenixData[$searchType]['returnDate'] = $line->returnDate;

            // set gal count and average it
            $phoenixData[$searchType]['averageGalTrips'] = isset($phoenixData[$searchType]['averageGalTrips']) ? ($phoenixData[$searchType]['averageGalTrips'] + $line->galTrips) / 2 : $line->galTrips;

            // set JetStar count and average it
            $phoenixData[$searchType]['averageJetstarTrips'] = isset($phoenixData[$searchType]['averageJetstarTrips']) ? ($phoenixData[$searchType]['averageJetstarTrips'] + $line->jetstarTrips) / 2 : $line->jetstarTrips;

            // we should get the last X provider counts and average them
            // so that we can detect when things are broken

            // get all the direction trips counts and average them
            $phoenixData[$searchType]['averageInboundTrips'] = isset($phoenixData[$searchType]['averageInboundTrips']) ? ($phoenixData[$searchType]['averageInboundTrips'] + $line->inboundTrips) / 2 : $line->inboundTrips;
            $phoenixData[$searchType]['averageOutboundTrips'] = isset($phoenixData[$searchType]['averageOutboundTrips']) ? ($phoenixData[$searchType]['averageOutboundTrips'] + $line->outboundTrips) / 2 : $line->outboundTrips;
            $phoenixData[$searchType]['averageCombinedTrips'] = isset($phoenixData[$searchType]['averageCombinedTrips']) ? ($phoenixData[$searchType]['averageCombinedTrips'] + $line->combinedTrips) / 2 : $line->combinedTrips;
        }



    }
} else {
    // error opening file
    die('there was an error loading the input file');
}
header("Content-Type:application/json");
print_r(json_encode($phoenixData));
fclose($phoenixFile);
