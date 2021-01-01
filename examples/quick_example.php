<?php

/*
An example script to demonstrate the usage of the smoothImageArc functions to draw circles.
*/

include('./smoothImageArc.php');

// create a 1000-by-1000 pixel image
$img = imagecreatetruecolor(1000, 1000);

// create some colour arrays
$blue = array(0, 0, 255, 0);
$green = array(0, 255, 0, 0);
$red = array(255, 0, 0, 0);

// draw some circles
imagesetthickness($img, 1);     // line thickness must be set to 1 pixel
smoothImageArc($img, 500, 350, 10, 250, 0, 360, $blue);
smoothImageArc($img, 350, 610, 10, 250, 0, 360, $green);
smoothImageArc($img, 650, 610, 10, 250, 0, 360, $red);

// save image to file
header("Content-type: image/png");
imagepng($img, "quick_example.png");

// free memory
imagedestroy($img);

?>