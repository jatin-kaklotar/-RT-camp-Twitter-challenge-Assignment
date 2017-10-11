<?php
ob_start();
session_start();
$menu = $_SESSION['my'];
$xml = new SimpleXMLElement('<xml/>');
foreach ($menu as $key => $value) {
    $track = $xml->addChild('root');
    $track->addChild('number', $key + 1);
    $track->addChild('tweet', $value[1]);
    if ($key == 24) {
        break;
    }
}
print($xml->asXML());
$filename = "my_tweets_" . date('Ymdhis') . ".xml";
header("Content-Disposition: attachment; filename=\"$filename\"");
Header('Content-type: text/xml');
ob_end_flush();
