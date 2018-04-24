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
{if (time() > strtotime( '00:03AM' ) && time() < strtotime( '00:08AM' ) ||
    time() > strtotime( '01:03AM' ) && time() < strtotime( '01:08AM' ) ||
    time() > strtotime( '02:03AM' ) && time() < strtotime( '02:08AM' ) ||
    time() > strtotime( '03:03AM' ) && time() < strtotime( '03:08AM' ) ||
    time() > strtotime( '04:03AM' ) && time() < strtotime( '04:08AM' ) ||
    time() > strtotime( '05:03AM' ) && time() < strtotime( '05:08AM' ) ||
    time() > strtotime( '06:03AM' ) && time() < strtotime( '06:08AM' ) ||
    time() > strtotime( '07:03AM' ) && time() < strtotime( '07:08AM' ) ||
    time() > strtotime( '08:03AM' ) && time() < strtotime( '08:08AM' ) ||
    time() > strtotime( '09:03AM' ) && time() < strtotime( '09:08AM' ) ||
    time() > strtotime( '10:03AM' ) && time() < strtotime( '10:08AM' ) ||
    time() > strtotime( '11:03AM' ) && time() < strtotime( '11:08AM' ) ||
    time() > strtotime( '12:03AM' ) && time() < strtotime( '12:08AM' ) ||
    time() > strtotime( '13:03AM' ) && time() < strtotime( '13:08AM' ) ||
    time() > strtotime( '14:03AM' ) && time() < strtotime( '14:08AM' ) ||
    time() > strtotime( '15:03AM' ) && time() < strtotime( '15:08AM' ) ||
    time() > strtotime( '16:03AM' ) && time() < strtotime( '16:08AM' ) ||
    time() > strtotime( '17:03AM' ) && time() < strtotime( '17:08AM' ) ||
    time() > strtotime( '18:03AM' ) && time() < strtotime( '18:08AM' ) ||
    time() > strtotime( '19:03AM' ) && time() < strtotime( '19:08AM' ) ||
    time() > strtotime( '20:03AM' ) && time() < strtotime( '20:08AM' ) ||
    time() > strtotime( '21:03AM' ) && time() < strtotime( '21:08AM' ) ||
    time() > strtotime( '22:03AM' ) && time() < strtotime( '22:08AM' ) ||
    time() > strtotime( '23:03AM' ) && time() < strtotime( '23:08AM' ))
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