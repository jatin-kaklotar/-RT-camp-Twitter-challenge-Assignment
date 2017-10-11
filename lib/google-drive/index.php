<?php
require_once("functions.php");
session_start();
header('Content-Type: text/html; charset=utf-8');
$authUrl = getAuthorizationUrl("", "");
?>
<!DOCTYPE html>
<html lang="fi">
<head>
	<title>Google Drive Login and Upload</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="col-md-12">
        <h3>Download Tweets into Google drive</h3>
        <ul>
            <li>You must have to Google Drive Account then Click on Login google button</li>
            <li>You will be asked to connect using your Google account</li>
            <li>Once authenticated, you will store upto 3200 tweets in your Google drive</li>
        </ul>
        <a href=<?php echo "'" . $authUrl . "'" ?>><button type="button" class="btn btn-primary">Login Google & Store</button></a>
        <a href=<?php echo "../../index.php" ?>><button type="button" class="btn btn-primary">Back</button></a>
    </div>
</div>
</body>
</html>