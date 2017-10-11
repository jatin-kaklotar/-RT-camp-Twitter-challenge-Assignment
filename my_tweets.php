<?php
session_start();
use Abraham\TwitterOAuth\TwitterOAuth;

require 'autoload.php';
require 'ConsumerKey.php';
$menu = array();
if (!isset($_SESSION['access_token'])) {
    header('Location: ./');
} else {
    if (isset($_SESSION['total_users'])) {
        $search_usr = $_SESSION['public_user'];
        $total_usr = $_SESSION['total_users'];
    } else {
        $search_usr = 1;
        $total_usr = array();
    }
    if (false !== $key = array_search($search_usr, $total_usr)) {
        $_SESSION['my'] = $_SESSION['total_user_tweets'][$key];
        if (isset($_SESSION['my'])) {
            echo "1";
        }
    } else {
        if (isset($_SESSION['public_user'])) {
            $public_user = $_SESSION['public_user'];
        } else {
            $public_user = $_SESSION['my_profile']['screen_name'];
            $_SESSION['public_user'] = $public_user;
        }
        $access_token = $_SESSION['access_token'];
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user = $connection->get("users/show", array('screen_name' => $public_user));
        $total_tweet_cnt = $user->statuses_count;
        $user_names = $user->screen_name;
        $followerstweet = $connection->get("statuses/user_timeline", array('screen_name' => $public_user, 'count' => 200, 'include_rts' => true));
        $menu = array();
        if (isset($followerstweet->error)) {
            array_push($menu, array(1, 'No access  available for this user'));
        } else {
            $ftweet = null;
            foreach ($followerstweet as $key => $ftweet) {
                array_push($menu, array($ftweet->id, $ftweet->text));
            }
            $oldest = $ftweet->id;
            if ($total_tweet_cnt > 200) {
                $totalLoop = 1;
                $new_cnt = $total_tweet_cnt / 200;
                $round_val = round($new_cnt, 0, PHP_ROUND_HALF_DOWN); //this for calculate loop
                if ($round_val > 15) {
                    $round_val = 16;
                }
                while ($totalLoop <= $round_val) {
                    $followerstweet = $connection->get("statuses/user_timeline", array('screen_name' => $public_user, 'count' => 200, 'max_id' => "$oldest", 'include_rts' => true));
                    $duplicateRemove = 1;
                    foreach ($followerstweet as $ftweet) {
                        if ($duplicateRemove != 1) {
                            array_push($menu, array($ftweet->id, $ftweet->text));
                        }
                        $duplicateRemove++;
                    }
                    $oldest = $ftweet->id;
                    $totalLoop++;
                }
            }
        }
        $_SESSION['my'] = $menu;
        if (isset($_SESSION['my'])) {
            if(isset($_SESSION['total_users'])) {
                if (!in_array($user_names, $_SESSION['total_users'])) {
                    $_SESSION['total_users'][] = $user_names;
                    $_SESSION['total_user_tweets'][] = $_SESSION['my'];
                }
            } else {
                $_SESSION['total_users'][] = $user_names;
                $_SESSION['total_user_tweets'][] = $_SESSION['my'];
            }
            echo "1";
        }
    }
}