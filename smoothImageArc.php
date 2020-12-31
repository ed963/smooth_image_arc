<?php

function smoothImageArc($img, $cx, $cy, $thickness, $radius, $start, $stop, $color) {
    /*
    Adapted from Ulrich Mierendorff's imageSmoothArc function
    http://www.ulrichmierendorff.com/software/antialiased_arcs.html

    Parameters:
         $img        - A PHP GD image resource, returned by one of the image creation functions, with 
                       line thickness set to 1 pixel (i.e. imagesetthickness($img, 1) has been called)
         $cx         - The x-coordinate of the centre of the arc
         $cy         - The y-coordinate of the centre of the arc
         $thickness  - The line thickness of the arc, in pixels
         $radius     - The radius of the arc, in pixels
         $start      - The arc's start angle, in degrees
         $stop       - The arc's end angle, in degrees
         $color      - The color of the arc as a four-element array corresponding to RGBA values

    Notes: 
       - $start and $stop must be multiples of 90
       - 0 degrees is located at the three-o-clock position, and the arc is drawn clockwise
       */
    /**
     * 
     */

    while ($start < 0) {
        $start += 360;
    }
    while ($start > 360) {
        $start -= 360;
    }

    while ($stop < 0) {
        $stop += 360;
    }
    while ($stop > 360) {
        $stop -= 360;
    }

    if ($start > $stop) {
        smoothImageArc($img, $cx, $cy, $thickness, $radius, $start, 360, $color);
        smoothImageArc($img, $cx, $cy, $thickness, $radius, 0, $stop, $color,);
    }
    else {
        for ($i = 0; $i <= 3; $i++) {
            if ($start <= $i * 90) {
                if ($stop >= ($i + 1) * 90) {
                    smoothArcSegment($img, $cx, $cy, $thickness, $radius, $color, $i);
                }
            }
        }
    }    
}

function smoothArcSegment($img, $cx, $cy, $thickness, $radius, $color, $corner) {
    /**
     * This function is a helper for smoothImageArc, and should not be called directly.
     */

    $fillColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], $color[3]);
    $depth = round($radius + 0.5 * $thickness);
    $inner = $depth - $thickness + 1;
    $cx = 1.0 * round($cx);
    $cy = 1.0 * round($cy);

    switch ($corner) {
        case 0:
            $xp = +1;
            $yp = +1;
            break;
        case 1:
            $xp = -1;
            $yp = +1;
            break;
        case 2:
            $xp = -1;
            $yp = -1;
            break;
        case 3:
            $xp = +1;
            $yp = -1;
            break;
    }

    for ($x = 0;
        $x <= $depth;
        $x++
    ) {
        $y = sqrt($depth * $depth - $x * $x);
        $error = $y - (int)($y);
        $y = (int)($y);

        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2],
            127 - (127 - $color[3]) * $error
        );
        imageSetPixel($img, $cx + $xp * $x, $cy + $yp * ($y + 1), $diffColor);

        if ($x <= $inner) {
            $y2 = sqrt($inner * $inner - $x * $x);
            $error = (int)($y2) + 1 - $y2;
            $y2 = (int)($y2) + 1;

            $diffColor = imageColorExactAlpha($img, $color[0], $color[1],
                $color[2],
                127 - (127 - $color[3]) * $error
            );
            imageSetPixel($img, $cx + $xp * $x, $cy + $yp * ($y2 - 1), $diffColor);

            imageLine($img, $cx + $xp * $x, $cy + $yp * $y, $cx + $xp * $x, $cy + $yp * $y2, $fillColor);
        } else {
            imageLine($img, $cx + $xp * $x, $cy + $yp * $y, $cx + $xp * $x, $cy, $fillColor);
        }
    }
    for ($y = 0; $y < $depth; $y++) {
        $x = sqrt($depth * $depth - $y * $y);
        $error = $x - (int)($x);
        $x = (int)($x);
        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
        imageSetPixel($img, $cx + $xp * ($x + 1), $cy + $yp * $y, $diffColor);
    }
    for ($y = 0; $y < $inner; $y++) {
        $x = sqrt($inner * $inner - $y * $y);
        $error = (int)($x) + 1 - $x;
        $x = (int)($x) + 1;
        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
        imageSetPixel($img, $cx + $xp * ($x - 1), $cy + $yp * $y,
            $diffColor
        );
    }
}

?>