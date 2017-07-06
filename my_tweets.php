<?php

session_start();
require 'autoload.php';
//require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'aeKsT8Hlp7kg0i91SLXK4NB6o'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'QRbDVwhbbJk6CokKdQN3dHjwSzve45V73uH24ZalLaQIM2QDuL'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'https://jatin-kaklotar.000webhostapp.com/twitter_demo/callback.php'); // your app callback URL

$menu = array();


if (!isset($_SESSION['access_token'])) {
    header('Location: ./');
} else {


    if (!isset($_SESSION['my'])) {

        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user = $connection->get("account/verify_credentials");
        $my_session = array('screen_name' => $user->screen_name, 'followers' => $user->followers_count, 'total_tweets' => $user->statuses_count);
        $total_tweet_cnt = $my_session['total_tweets'];


        $followerstweet = $connection->get("statuses/user_timeline", array('count' => 200, 'include_rts' => true));
        $menu = array();
        foreach ($followerstweet as $key => $ftweet) {
            array_push($menu, array($ftweet->id, $ftweet->text));

        }

        $oldest = $ftweet->id;
        if ($total_tweet_cnt > 200) {

            $t = 1;
            $new_cnt = $total_tweet_cnt / 200;
            $round_val = round($new_cnt, 0, PHP_ROUND_HALF_DOWN); //this for calculate loop

            if ($round_val > 15) {
                $round_val = 16;
            }


            while ($t <= $round_val) {
                $followerstweet = $connection->get("statuses/user_timeline", array('count' => 200, 'max_id' => "$oldest", 'include_rts' => true));
                $k = 1;
                foreach ($followerstweet as $ftweet) {
                    //                           array_push($menu,$ftweet->text);
                    array_push($menu, array($ftweet->id, $ftweet->text));
                    $k++;
                }
                $oldest = $ftweet->id;
                $t++;
            }

        }
        $_SESSION['my'] = $menu;
        if (isset($_SESSION['my'])) {
            echo "1";
        }

    } else {
        echo "1";
    }


}


?>