<?php

//set timezone manila
date_default_timezone_set('Asia/Manila');

//imagecompare call
require 'ImageCompare.php';

//twitteroauth call
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

//set keys
define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
$access_token = '';
$access_token_secret = '';

//object 
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");

//website url
$siteURL = "https://upcat.up.edu.ph/results/index.html";

//call Google PageSpeed Insights API
$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true&strategy=mobile");

//decode json data
$googlePagespeedData = json_decode($googlePagespeedData, true);

//screenshot data
$screenshot = $googlePagespeedData['screenshot']['data'];
$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot); 
$data = base64_decode($screenshot);

//
$im = imagecreatefromstring($data);
    if ($im !== false) 
        {
        // saves an image to specific location
        $resp = imagepng($im, "latest.jpg");
        // frees image from memory
        imagedestroy($im);
        }
    else 
        {
        // show if any error in bytes data for image
        echo 'An error occurred.'; 
        }

//sleep to make sure the image is already done processing       
sleep(15);

//set variables
$postdate = date('h:i A, jS \of F Y');
$class = new compareImages;

//image comparing, if no difference, tweet that results are not yet out
if ($class->compare('comparison.jpg','latest.jpg') < 10)
{if (time() > strtotime( '00:03' ) && time() < strtotime( '00:08' ) ||
    time() > strtotime( '01:03' ) && time() < strtotime( '01:08' ) ||
    time() > strtotime( '02:03' ) && time() < strtotime( '02:08' ) ||
    time() > strtotime( '03:03' ) && time() < strtotime( '03:08' ) ||
    time() > strtotime( '04:03' ) && time() < strtotime( '04:08' ) ||
    time() > strtotime( '05:03' ) && time() < strtotime( '05:08' ) ||
    time() > strtotime( '06:03' ) && time() < strtotime( '06:08' ) ||
    time() > strtotime( '07:03' ) && time() < strtotime( '07:08' ) ||
    time() > strtotime( '08:03' ) && time() < strtotime( '08:08' ) ||
    time() > strtotime( '09:03' ) && time() < strtotime( '09:08' ) ||
    time() > strtotime( '10:03' ) && time() < strtotime( '10:08' ) ||
    time() > strtotime( '11:03' ) && time() < strtotime( '11:08' ) ||
    time() > strtotime( '12:03' ) && time() < strtotime( '12:08' ) ||
    time() > strtotime( '13:03' ) && time() < strtotime( '13:08' ) ||
    time() > strtotime( '14:03' ) && time() < strtotime( '14:08' ) ||
    time() > strtotime( '15:03' ) && time() < strtotime( '15:08' ) ||
    time() > strtotime( '16:03' ) && time() < strtotime( '16:08' ) ||
    time() > strtotime( '17:03' ) && time() < strtotime( '17:08' ) ||
    time() > strtotime( '18:03' ) && time() < strtotime( '18:08' ) ||
    time() > strtotime( '19:03' ) && time() < strtotime( '19:08' ) ||
    time() > strtotime( '20:03' ) && time() < strtotime( '20:08' ) ||
    time() > strtotime( '21:03' ) && time() < strtotime( '21:08' ) ||
    time() > strtotime( '22:03' ) && time() < strtotime( '22:08' ) ||
    time() > strtotime( '23:03' ) && time() < strtotime( '23:08' ))
    {$statues = $connection->post("statuses/update", ["status" => "NOT YET \n$postdate"])
    ;}
    ;} 


//if there is a difference, post the latest snapshot
if ($class->compare('comparison.jpg','latest.jpg') > 10) {

{$media1 = $connection->upload('media/upload', ['media' => 'latest.jpg']);
$parameters = [
    'status' => "An update has been detected in the webpage.\n$postdate\n\nVERIFY IN THE ATTACHED IMAGE \nOR DOUBLE CHECK HERE:\nmain: upcat.up.edu.ph/results\nmirror: upcat.stickbread.net",
    'media_ids' => implode(',', [$media1->media_id_string])
];
$result = $connection->post('statuses/update', $parameters);
};
};

echo $class->compare('comparison.jpg','latest.jpg');

?>
