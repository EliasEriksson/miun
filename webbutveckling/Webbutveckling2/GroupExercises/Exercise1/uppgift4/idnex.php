<?php
function pyramid($width) {
    for ($i = -$width + 1; $i <= $width;  $i++) {
        echo trim(str_repeat("* ", $width - abs($i)), " ");
        if ($i != $width) {
            echo "<br>";
        }
    }
}

pyramid(5);
