<?php
$xmlString = file_get_contents(__DIR__. "\aquadar.xml");

$xml = new SimpleXMLElement($xmlString);

foreach ($xml->item as $item) {
    echo $item->title;
    echo "<br>";
    echo $item->price;
    echo "<br>";
    echo $item->code;
    echo "<br>";
    echo $item->brand;
    echo "<br>";
}