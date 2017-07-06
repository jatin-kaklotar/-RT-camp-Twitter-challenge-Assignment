<!--https://www.000webhost.com/members/website/jatin-kaklotar/build-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter App</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="lib/css/style.css">
    <script src="lib/js/index.js"></script>

</head>
<body>

<?php
session_start();
require 'autoload.php';

//require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'aeKsT8Hlp7kg0i91SLXK4NB6o'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'QRbDVwhbbJk6CokKdQN3dHjwSzve45V73uH24ZalLaQIM2QDuL'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'https://jatin-kaklotar.000webhostapp.com/twitter_demo/callback.php'); // your app callback URL


if (!isset($_SESSION['access_token']))
{
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

    ?>

    <div class="container-fluid">

        <div class="col-md-12">
            <h2>Hello User this is Twitter Timeline Challenge</h2>
            <ul>
                <li>You have to first require login</li>
                <li>You will be asked to connect using your twitter account</li>
                <li>Once authenticated, you will show latest 10 tweets form your "home" timeline in slide show.</li>
                <li>Below jQuery-slideshow you will display list 10 followers(10 random followers).</li>
                <li>In search followers box you starts typing, your followers will start showing up.</li>
                <li>When you will click on a follower name, 10 tweets from that follower's user-timeline will be displayed in same jQuery-slider </li>
                <li>You can download upto 3200 tweets using  Download button</li>
            </ul>

            <a href="<?php echo $url; ?>">
                <button type="button" class="btn btn-primary">Twitter Login</button>
            </a>

        </div>

    </div>
    <?php
}
else
{

$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);


$user = $connection->get("account/verify_credentials");
$session_account_info = array('screen_name' => $user->screen_name, 'followers' => $user->followers_count);
$_SESSION['my_profile'] = $session_account_info;


?>


<nav class="navbar navbar-inverse  navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Twitter-Timeline</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#" data-toggle="modal" data-target="#myModal" class="download_tweet"><span
                                class="glyphicon glyphicon-download-alt"></span> Download</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid" style="margin-top:46px">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div id="slider">
            <a href="#" class="control_next">>></a>
            <a href="#" class="control_prev"><<</a>
            <ul id="div66">
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>

</div>

<br>

<div class="container-fluid">

    <!--    <div class="col-md-12 ">-->
    <!---->
    <!--    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Download Tweets</button>-->
    <!--        </div>-->
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Download Tweets</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel1">Select option for download tweets:</label>
                        <select class="form-control download_select" id="sel1" onchange="location = this.value;">
                            <option value="#">Select</option>
                            <option value="lib/google-drive/index.php">Google Drive</option>
                            <option value="csv_try.php">CSV</option>
                            <option value="json_try.php">Json</option>
                            <option value="xls_try.php">Xls</option>
                            <option value="xml_try.php">Xml</option>
                        </select>
                        <div class="download_msg" style="display: none">Wait few seconds for loading tweets</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>



<script>
    $(document).ready(function () {


        $('.btnfollowertwitt').click(function () {

            var followerID = $(this).val();

            $.ajax({
                type: 'get',
                url: 'get_tweets.php', //Here you will fetch records
                data: 'followerID=' + followerID, //Pass $id
                dataType: 'json',

                success: function (result) {

                    var htmld = "";
                    $.each(result['display_array'], function (index, data) {
                        htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
                    });

                    var tweet_lenth = result['display_array'].length;
                    if (tweet_lenth == 1) {
                        htmld += '<li></li>';
                    }
                    else if (tweet_lenth == 0) {
                        htmld += '<li></li><li>No tweets found</li>';
                    }

                    $("#div66").html(htmld);
                }
            });

        });


        $.ajax({
            type: 'get',
            url: 'get_tweets.php', //Here you will fetch records
            data: {followerID: null}, //Pass $id
            dataType: 'json',
            success: function (result) {

                var htmld = "";
                $.each(result['display_array'], function (index, data) {
                    htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
                });


                $("#div66").html(htmld);

            }
        });

    });
</script>


<script>
    $(document).ready(function () {
        $("#search-box").keyup(function () {

            if ($(this).val() != '') {
                $.ajax({
                    type: "POST",
                    url: "search_followers.php",
                    data: 'keyword=' + $(this).val(),

                    success: function (data) {
                        $("#suggesstion-box").show();
                        $("#suggesstion-box").html(data);

                        $("#search-box").css("background", "#FFF");
//                        $("#msg-box").show();
                    },

                    beforeSend: function () {
                        $("#msg-box").show();
                    },
                    complete: function () {
                        $("#msg-box").hide();
                    }


                });
            }
            else {
                $("#suggesstion-box").empty();

            }

        });
    });


    function selectFollowers(val) {

        $("#search-box").val(val);

        $.ajax({
            type: 'get',
            url: 'get_tweets.php', //Here you will fetch records
            data: 'followerID=' + val, //Pass $id
            dataType: 'json',

            success: function (result) {

                var htmld = "";
                if (result['display_array'] == '') {

                    htmld += '<li></li><li>No tweets found</li>';
                }
                else {

                    var tweet_lenth = result['display_array'].length;
                    if (tweet_lenth == 1) {
                        htmld += '<li></li>';
                    }

                    $.each(result['display_array'], function (index, data) {
                        htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
                    });

                }
                $("#div66").html(htmld);
            }
        });

        $("#suggesstion-box").hide();
    }
</script>


<script>
    $('.download_tweet').click(function () {

        $(".download_select").prop("disabled", true);
        $(".download_msg").show();
        $.ajax({
            type: 'get',
            url: 'my_tweets.php', //Here you will fetch records
            dataType: 'json',
            success: function (result) {

                if (result == 1) {
                    $(".download_select").prop("disabled", false);
                    $(".download_msg").hide();

                }
                else {
                    alert("something went wrong please try again");
                }


            }
        });

    });
</script>


<br><br>
<div class="container-fluid">
    <div class="col-md-12 col-sm-12">
        <div class="frmSearch">
            <input type="text" id="search-box" placeholder="Enter Follower Name"/>
            <div id="msg-box" style="display: none;">Wait few seconds....</div>
            <div id="suggesstion-box"></div>
        </div>

        <?php
        $followerslist = $connection->get("followers/list", array('count' => 10));

        foreach ($followerslist->users as $follwers_random) {
            echo "<br/><br/><img src='$follwers_random->profile_image_url_https'>";
            //  echo " ".$ff->name;
            ?>
            <button class='btnfollowertwitt btn btn btn-lg '
                    value='<?php echo $follwers_random->screen_name; ?>'><?php echo $follwers_random->name; ?></button>
            <?php

        }

        }
        ?>

    </div>
</div>

</body>
</html>