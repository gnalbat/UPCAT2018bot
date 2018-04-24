<?php
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

//display screenshot image
$im = imagecreatefromstring($data);
if ($im !== false) 
{
    // saves an image to specific location
    $resp = imagepng($im, "comparison.jpg");
    // frees image from memory
    imagedestroy($im);
}
else 
{
    // show if any error in bytes data for image
    echo 'An error occurred.'; 
}
?>