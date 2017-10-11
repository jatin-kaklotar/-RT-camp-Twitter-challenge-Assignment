<?php
session_start();
$menu = $_SESSION['my'];
$data = array();
foreach ($menu as $key => $value) {
    $data[$key]['number'] = $key + 1;
    $data[$key]['tweet'] = $value[1];
}
$json = json_encode($data);
$title_json = "my_tweets_" . date('Ymdhis');
header('Content-disposition: attachment; filename=' . $title_json . '.json');
header('Content-type: application/json');
echo $json;