<?php
//session_start();
require 'autoload.php';
//require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'aeKsT8Hlp7kg0i91SLXK4NB6o'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'QRbDVwhbbJk6CokKdQN3dHjwSzve45V73uH24ZalLaQIM2QDuL'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'https://jatin-kaklotar.000webhostapp.com/twitter_demo/callback.php'); // your app callback URL

$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$myprofile_value = $_SESSION['my_profile'];
$my_scrren_name = $myprofile_value['screen_name'];
$followerslist = $connection->get("followers/ids", array('screen_name' => $my_scrren_name, 'count' => 5000));


$cnt = 0;
$var_assign = 0;
$loop_cnt = '';
foreach ($followerslist->ids as $followr_id) {
    if ($cnt % 100 == 0) {
        $var_assign = $var_assign + 1;
        $loop_cnt = $var_assign;
        ${"var$var_assign"}='';
    }
    ${"var$var_assign"} = ${"var$var_assign"} . "," . $followr_id;    //Concatenate followers id in comma seperated format
    $cnt = $cnt + 1;
}

$response_array = array();
$new = 1;
for ($i = 1; $i <= $loop_cnt; $i++) {
    $id_lookup = $connection->get("users/lookup", array('user_id' => ${"var$i"}));
    foreach ($id_lookup as $key => $value) {
        $response_array[$new]['id'] = $value->id;
        $response_array[$new]['name'] = $value->screen_name;
        $new = $new + 1;
    }

}

$_SESSION['data'] = $response_array;
//echo "<pre>";
//print_r($_SESSION['data']);

?>





