<?php

include 'JsonConverter.php';

$jC = new JsonConverter();
$arr = $jC->setDataReverse();

echo '<pre>';
print_r($arr);
echo '</pre>';

$jC->generateXML($arr);

?>

<!--<!doctype html>-->
<!--<html lang="en">-->
<!--    <head>-->
<!--        <meta charset="UTF-8">-->
<!--        <title>Document</title>-->
<!--    </head>-->
<!--    <body>-->
<!---->
<!--        --><?php
//            $jC = new JsonConverter();
//            //$xml = $xmlMerger->getDataFolder();
//            $arr = $jC->setDataReverse();
//            $xml = $jC->generateXML($arr);
//            echo '<pre>';
//            print_r($xml);
//            echo '</pre>'
//        ?>
<!---->
<!--    </body>-->
<!--</html>-->