<?php

error_reporting(0);

require_once "RecordImporterProduct.php";
require_once "db.php";
require_once "../config.php";
require_once ('vendor/autoload.php');

use Importer\RecordImporterProduct;
use \Dejurin\GoogleTranslateForFree;

$xmlString = file_get_contents(__DIR__. "\aquadar.xml");
$image_path = $_SERVER['DOCUMENT_ROOT'].'/image/catalog/products_import/';

$xml = new SimpleXMLElement($xmlString);
$google_trans = new GoogleTranslateForFree();
$db = new Sqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$db->connect();

$counter = 700;

$file = new SplFileObject('counter.txt', 'r');
$counter = (int)$file->fgets();
$file = null;

// Количество объектов
$num = 1;
$end_num = $counter + $num;

while ($counter < count($xml->item)) {

    if ($counter < $end_num) {

        $item = $xml->item[$counter];

        $importer = new RecordImporterProduct($item, $db, $google_trans);
        $res = $importer->createRecordProduct();

        if ($res[0] != 0) {
            echo "
            {
              \"id\": $res[0],
              \"counter\": $counter,
              \"desc\": \"".addslashes($res[1])."\",              
              \"symbols\": $res[2]            
            }
            ";

            // В случае успеха увеличиваем счетчик на 1
            $file = new SplFileObject('counter.txt', 'w');
            $file->fwrite($counter+1);
            $file = null;

        }
        else {
            echo "BAD: id = $res[0], counter = $counter<br><br>";
            break;
        }

       $counter++;
    } else {
        break;
    }
}