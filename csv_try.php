<?php
/**
 * Created by PhpStorm.
 * User: jatin
 * Date: 8/6/17
 * Time: 10:53 PM
 */

session_start();

$menu=$_SESSION['my'];
$data=array();
foreach ($menu as $key=>$value)
{

    $data[$key]['number']=$key+1;
    $data[$key]['tweet']=$value[1];
}


function cleanData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// filename for download
$filename = "my_tweets_" . date('Ymdhis') . ".csv";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/csv");

$flag = false;
foreach($data as $row) {
    if(!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}

die;


function convert_to_csv($input_array, $output_file_name, $delimiter)
{
    $temp_memory = fopen('php://memory', 'w');
    foreach ($input_array as $line) {
        fputcsv($temp_memory, $line, $delimiter);
    }

    fseek($temp_memory, 0);
   // modify the header to be CSV format
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    fpassthru($temp_memory);
}

$title_csv = date("Ymdhis");


convert_to_csv($menu, $title_csv.'.csv', ',');


