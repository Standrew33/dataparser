<?php

include 'XMLMerger.php';

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
    </head>
    <body>

        <?php
            $xmlMerger = new XMLMerger();
            //$xml = $xmlMerger->getDataFolder();
            $xml = $xmlMerger->setDataReverse();
            echo '<pre>';
            print_r($xml);
            echo '</pre>'
        ?>

    </body>
</html>