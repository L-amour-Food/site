<?php



include __DIR__.'/plats/plats.php';

function me() {
    foreach(func_get_args() as $arg) {
        echo "<hr><pre>".htmlentities(print_r($arg, true))."</pre>";
    }

    exit;
}