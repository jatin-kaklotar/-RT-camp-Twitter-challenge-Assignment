<?php
session_start();
$arrayValue = $_SESSION['my'];
$tweetValue = "";
foreach ($arrayValue as $key => $value) {
    $result = preg_replace("/[^a-zA-Z0-9 # @ $ ( ) &  ? _: . \/ ]+/", " ", html_entity_decode($value[1], ENT_QUOTES));
    $tweetValue .= $key + 1 . "." . $result . "\n \n";
}
echo $tweetValue;