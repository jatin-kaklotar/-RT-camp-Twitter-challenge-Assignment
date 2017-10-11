<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter App</title>
    <link rel="shortcut icon" href="https://abs.twimg.com/favicons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="lib/js/index.js"></script>
</head>
<body>
<?php
use Abraham\TwitterOAuth\TwitterOAuth;

require 'autoload.php';
require 'ConsumerKey.php';
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
                <li>In search followers box you starts typing, public user and followers will start showing up.</li>
                <li>When you will click on a search user name, 10 tweets from that user's user-timeline will be displayed in same jQuery-slider</li>
                <li>You can download upto 3200 tweets using Download button</li>
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
                    <li>
                        <a href="#" data-toggle="modal" data-target="#myModal" class="download_tweet">
                            <span  class="glyphicon glyphicon-download-alt"></span> Download
                        </a>
                    </li>
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
                <ul id="sliderDiv">
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div id="myModal1" class="modal fade" role="dialog">
            <div class="modal-dialog ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Link for view download file</h4>
                    </div>
                    <div class="modal-body">
                        <h3><a id="linkinfo" href="#" target="_blank">View File</a></h3>
                        <p><b>Note</b>: this is excel file upload on drive when you open this link then please select google sheet option for view tweets</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
                            <select class="form-control download_select" id="downloadAll">
                                <option value="#">Select</option>
                                <option value="lib/google-drive/index.php">Google Drive</option>
                                <option value="DownloadCSV.php">CSV</option>
                                <option value="DownloadJson.php">Json</option>
                                <option value="DownloadXls.php">Xls</option>
                                <option value="DownloadXml.php">Xml</option>
                                <option value="pdf">Pdf</option>
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
    <?php
    if (isset($_GET['linkdrive'])) {
        $link_file = $_GET['linkdrive'];
        ?>
        <script type="text/javascript">
            var link_drive = "<?php echo $link_file; ?>";
            $('#myModal1').modal('show');
            $("#linkinfo").prop("href", link_drive);
        </script>
        <?php
    } ?>
    <script src="lib/js/TwitterJS.js"></script>
    <br><br>
    <div class="container-fluid">
        <div class="col-md-9 col-md-offset-3 col-sm-12">
            <div class="frmSearch col-md-10 col-md-offset-2 col-sm-12" style="padding-left: 43px">
                <form action='' method='post'>
                    <input type='text' name='country' value='' placeholder="search here.." class='search-box'
                           id='search-box'>
                    <span class="searchMessage" style="display: none;">Wait few seconds....</span>
                </form>
                <br><br>
            </div>
            <div class="col-md-12 col-sm-12">
<?php
                $followerslist = $connection->get("followers/list", array('count' => 10));
                if (isset($followerslist->errors[0]->code)) {
                    echo "<div class='col-md-10 col-md-offset-2 col-sm-12'>Rate Limit exceesed please refresh page after some minutes</div>";
                } else {
                    foreach ($followerslist->users as $follwers_random) {
                        echo "<div class='col-md-6  col-sm-12'  style='padding-bottom:40px;'>";
                        echo "<img src='$follwers_random->profile_image_url_https'>";
                        echo "<button class='btnfollowertwitt btn btn btn-lg' value='$follwers_random->screen_name'>$follwers_random->name</button>";
                        echo "</div>";
                    }
                }
}
?>
            </div>
        </div>
    </div>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="lib/js/TwitterSearchJS.js"></script>
</body>
</html>