<?php

session_start();
require 'autoload.php';
//require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'aeKsT8Hlp7kg0i91SLXK4NB6o'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'QRbDVwhbbJk6CokKdQN3dHjwSzve45V73uH24ZalLaQIM2QDuL'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'https://jatin-kaklotar.000webhostapp.com/twitter_demo/callback.php'); // your app callback URL


if (!isset($_SESSION['access_token'])) {
    header('Location: ./');
} else {

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    if ($_REQUEST['followerID'] != null) {
        $follower_id1 = $_REQUEST['followerID'];
        $followerstweet = $connection->get("statuses/user_timeline", array('screen_name' => $follower_id1, 'count' => 10));
        $my_array = array();
        foreach ($followerstweet as $key => $ftweet) {
            $my_array[$key]['text'] = $ftweet->text;
            if (isset($ftweet->entities->media[0]->media_url)) {
                $image_f = '<img src="' . $ftweet->entities->media[0]->media_url . '" height="150" width="150" /> &nbsp;';
                $my_array[$key]['images'] = $image_f;
            } else {
                $my_array[$key]['images'] = '';
            }
        }

    } else {

        $followerstweet = $connection->get("statuses/home_timeline", array('count' => 10));
        $my_array = array();
        foreach ($followerstweet as $key => $ftweet) {

            $my_array[$key]['text'] = $ftweet->text;
            if (isset($ftweet->entities->media[0]->media_url)) {
                $image_f = '<img src="' . $ftweet->entities->media[0]->media_url . '" height="150" width="150" /> &nbsp;';
                $my_array[$key]['images'] = $image_f;
            } else {
                $my_array[$key]['images'] = '';
            }

        }
    }

    echo json_encode(array('success' => 'true', 'display_array' => $my_array));

}

?>