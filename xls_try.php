<?php
//https://stackoverflow.com/questions/11545661/php-force-download-json
session_start();
$menu = $_SESSION['my'];


//$data = [
//    ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
//    ["firstname" => "Amanda", "lastname" => "Miller", "age" => 18],
//    ["firstname" => "James", "lastname" => "Brown", "age" => 31],
//    ["firstname" => "Patricia", "lastname" => "Williams", "age" => 7],
//    ["firstname" => "Michael", "lastname" => "Davis", "age" => 43],
//    ["firstname" => "Sarah", "lastname" => "Miller", "age" => 24],
//    ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
//    ["firstname" => "Patrick", "lastname" => "Miller", "age" => 27]
//];
//


$data = array();
foreach ($menu as $key => $value) {

    $data[$key]['number'] = $key + 1;
    $data[$key]['tweet'] = $value[1];
}

function cleanData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// filename for download
$filename = "my_tweets_" . date('Ymdhis') . ".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

$flag = false;
foreach ($data as $row) {
    if (!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}


?>