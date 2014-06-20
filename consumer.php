<?php

$phoenixFile = fopen('example-data.txt', 'r');

$phoenixData = array();

// make sure it exists (and we have access)
if ($phoenixFile) {
    while (!feof($phoenixFile)) {
        $line = fgets($phoenixFile);
        $line = json_decode($line);

        if (array_key_exists($line->test, $phoenixData)) {

        }
    }
} else {
    // error opening file
    die('there was an error loading the input file');
}
echo "<pre>";
print_r($phoenixData);
fclose($phoenixFile);
