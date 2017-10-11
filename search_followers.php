<?php
session_start();
use Abraham\TwitterOAuth\TwitterOAuth;

require 'autoload.php';
require 'ConsumerKey.php';
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
if (isset($_GET['term'])) {
    if (!isset($_SESSION['data'])) {
        include_once "getfollowerslist_ajax.php";
    }
    $keyword = $_GET['term'];
    $my_search = array();
    $my_search = $_SESSION['data'];
    $public_user = array();
    for ($i = 1; $i <= 3; $i++) {
        $followerslist = $connection->get("users/search", array('q' => $keyword, 'count' => 20, 'page' => $i));
        foreach ($followerslist as $key => $value) {
            $public_user[] = $value->screen_name;
        }
    }
    $follower_session = $my_search;
    $followername_array = array();
    foreach ($follower_session as $key => $follw_value) {
        $followername_array[$key] = $follw_value['name'];
    }
    $input = preg_quote($keyword, '~');
    $result1 = preg_grep('~' . $input . '~', $followername_array);
    $final_result = array_merge($result1, $public_user);
    if (empty($final_result)) {
        $final_result = array("No user found");
    }
    echo json_encode($final_result);
}
?>

