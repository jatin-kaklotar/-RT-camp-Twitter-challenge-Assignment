<?php
session_start();
$menu = $_SESSION['my'];
$data = array();
foreach ($menu as $key => $value) {
    $data[$key]['number'] = $key + 1;
    $result = preg_replace("/[^a-zA-Z0-9 # @ $ ( ) &  ? _: . \/ ]+/", " ", html_entity_decode($value[1], ENT_QUOTES));
    $data[$key]['tweet'] = str_replace(',', ' ', $result);
}
function cleanData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

$filename = "my_tweets_" . date('Ymdhis') . ".csv";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/csv");
$flag = false;
foreach ($data as $row) {
    if (!$flag) {
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}