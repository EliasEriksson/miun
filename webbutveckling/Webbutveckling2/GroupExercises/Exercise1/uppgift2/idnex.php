<ul>
<?php

$colors = ["Röd", "Blå", "Grön", "Blå", "Rosa"];
foreach ($colors as $color) {
    echo "<li>$color</li>";
}

?>
</ul>
<?php
echo "Antal färger: " . count($colors);