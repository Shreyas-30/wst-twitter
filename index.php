<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter App</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/style.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="lib/js/index.js"></script>
</head>
<body>
<?php
use Abraham\TwitterOAuth\TwitterOAuth;

require 'autoload.php';
require 'ConsumerKey.php';
if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    ?>
    <div class="container-fluid">
        <div class="col-md-12">
            <h2>Twitter Timeline Challenge</h2>
            <p>Click to login to twitter</p>
            <a href="<?php echo $url; ?>">
                <button type="button" class="btn btn-primary">Login</button>
            </a>
        </div>
    </div>
    <?php
}
else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");
    $session_account_info = array('screen_name' => $user->screen_name, 'followers' => $user->followers_count);
    $_SESSION['my_profile'] = $session_account_info;
    ?>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="#">Twitter Timeline Challenge</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Logout <span class="glyphicon glyphicon-log-out"></span> </a></li>
                </ul>
            </div>
        </div>
    </nav>
<!-- NON RESPONSIVE NAV BAR -->
<!--    <nav class="navbar navbar-inverse  navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Twitter Timeline Challenge</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#" data-toggle="modal" data-target="#myModal" class="download_tweet">
                            Download <span  class="glyphicon glyphicon-download-alt"></span>
                        </a>
                    </li>
                    <li><a href="logout.php">Logout <span class="glyphicon glyphicon-log-out"></span> </a></li>
                </ul>
            </div>
        </div>
    </nav> -->
    <div class="container-fluid" style="margin-top:20px">
        <div class="col-xs-12">
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
    <script src="lib/js/TwitterJS.js"></script>
    <br><br>
    <div class="container-fluid">
        <div class="col-md-9 col-md-offset-3 col-sm-12">
            <div class="col-md-12 col-sm-12">
                <?php
                $followerslist = $connection->get("followers/list", array('count' => 10));
                if (isset($followerslist->errors[0]->code)) {
                    echo "<div class='col-md-10 col-md-offset-2 col-sm-12'>Rate Limit exceeded please try after some time</div>";
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
</body>
</html>