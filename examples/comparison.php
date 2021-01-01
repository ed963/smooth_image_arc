<?php

/*
An example script to compare the usage and ouput of the smoothImageArc and GD imagearc functions. 
*/

include('./smoothImageArc.php');

// create a 1000-by-400 pixel image
$img = imagecreatetruecolor(1000, 400);

// draw a simple arc using imagearc
$blue = imagecolorallocate($img, 0, 0, 255);
imagesetthickness($img, 10);
imagearc($img, 100, 300, 500, 500, -90, 0, $blue);

// draw the same arc using smoothImageArc
$blueArray = array(0, 0, 255, 0);
imagesetthickness($img, 1);     // line thickness must be set to 1 pixel
smoothImageArc($img, 600, 300, 10, 250, -90, 0, $blueArray);

// output image in the browser
header("Content-type: image/png");
imagepng($img, "comparison.png");

// free memory
imagedestroy($img);

?>