[<?php

require_once('../db/repository.php');

$pixels = get_pixels();

$resp = "";
foreach ($pixels as $zone) {
    $resp = $resp . '{x:' . $zone['x'] . ', y:' . $zone['y'] . ', w:' . $zone['w'] . ', h:' . $zone['h'];
    if ($zone['picture']) {
        $resp = $resp . ',picture:' . $zone['picture'];
    }
    $resp = $resp . '},';
}

$resp = substr($resp, 0, -1);

echo $resp;

?>
]