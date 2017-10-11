<?php
session_start();
use Abraham\TwitterOAuth\TwitterOAuth;

require 'autoload.php';
require 'ConsumerKey.php';
if (!isset($_SESSION['access_token'])) {
    header('Location: ./');
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    if ($_REQUEST['followerID'] != null) {
        $follower_id1 = $_REQUEST['followerID'];
        //session set for download tweet user
        $_SESSION['public_user'] = $follower_id1;
        $followerstweet = $connection->get("statuses/user_timeline", array('screen_name' => $follower_id1, 'count' => 10));
        $my_array = array();
        foreach ($followerstweet as $key => $ftweet) {
            $string_text = $ftweet->text;
            $my_array[$key]['text'] = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a target='_blank' href=\"\\0\">\\0</a>", $string_text);
            if (isset($ftweet->entities->media[0]->media_url)) {
                $image_f = '<img src="' . $ftweet->entities->media[0]->media_url_https . '" height="150" width="150" /> &nbsp;';
                $my_array[$key]['images'] = $image_f;
            } else {
                $my_array[$key]['images'] = '';
            }
        }
    } else {
        $followerstweet = $connection->get("statuses/home_timeline", array('count' => 10));
        $my_array = array();
        if (isset($followerstweet->errors[0]->code)) {
            $my_array[0]['text'] = "Rate Limit exceesed please refresh page after some minutes";
            $my_array[0]['images'] = '';
        } else {
            $_SESSION['public_user'] = $_SESSION['my_profile']['screen_name'];
            foreach ($followerstweet as $key => $ftweet) {
                $string_text = $ftweet->text;
                $my_array[$key]['text'] = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a target='_blank' href=\"\\0\">\\0</a>", $string_text);
                if (isset($ftweet->entities->media[0]->media_url)) {
                    $image_f = '<img src="' . $ftweet->entities->media[0]->media_url_https . '" height="150" width="150" /> &nbsp;';
                    $my_array[$key]['images'] = $image_f;
                } else {
                    $my_array[$key]['images'] = '';
                }

            }
        }
    }
    echo json_encode(array('success' => 'true', 'display_array' => $my_array));
}