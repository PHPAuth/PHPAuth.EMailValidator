<?php

echo "Importing " . __DIR__ . "/src/blacklist/*.json files... " . PHP_EOL;

$banned = [];
foreach (glob(__DIR__ . '/src/blacklist/?.json') as $fn) {
    echo "Importing {$fn} " . PHP_EOL;
    $j = file_get_contents($fn);
    $banned = array_merge($banned, json_decode($j, true));
}

echo "Importing [ NEW_DOMAINS.TXT ]" . PHP_EOL;

$fn_new = __DIR__ . '/new_domains.txt';
if (is_readable($fn_new)) {
    $banned = array_merge($banned, file($fn_new));
}

// trim
$banned = array_map(static function($value) {
    return trim($value);
}, $banned);

// unique
$banned = array_unique($banned);

// sort natural
sort($banned, SORT_NATURAL);

// split to chunks by first letter
$chunks = [];

echo "Exporting new *.json files to " . __DIR__ . PHP_EOL;

foreach ($banned as $domain) {
    $letter = substr($domain, 0, 1);
    $chunks[ $letter ][] = $domain;
}

// write chunks to files
foreach ($chunks as $letter => $list) {
    echo
    file_put_contents(__DIR__ . "/{$letter}.json", json_encode($list, JSON_PRETTY_PRINT));
}

echo "Done." . PHP_EOL . PHP_EOL;