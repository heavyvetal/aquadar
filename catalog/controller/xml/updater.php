<?php

class ControllerXmlUpdater extends Controller {
    public function index()
    {
        $xml_string = file_get_contents("https://ecosoft.ua/aquadar_ua.xml");
        $xml = new SimpleXMLElement($xml_string);

        // Для товаров с пустым sku ищем совпадения по названиям
        $empty_code_products = $this->db->query("
            SELECT p.product_id, p.sku, pd.name, pd.language_id, p.price  
            FROM `oc_product` p 
            LEFT JOIN `oc_product_description` pd 
            ON p.product_id=pd.product_id  
            WHERE p.sku='' AND pd.language_id=3
        ")->rows;

        foreach ($xml->item as $obj) {
            $code = $obj->code;
            $title = $obj->title;
            $price = $obj->price;

            // Товары без цен не обновляем
            if ($price != 0) {
                // Товары с заполненным sku
                if ($code != '') {
                    $product = $this->db->query("
                    SELECT p.product_id, p.sku, p.price  
                    FROM `oc_product` p 
                    WHERE p.sku='".$code."'
                    LIMIT 1
                ")->rows;

                    // Проверка существования товара в базе
                    if ($product[0]['sku'] == $code) {
                        $this->db->query("UPDATE `oc_product` SET `price`=$price WHERE `sku`='".$code."'");
                        echo "Updated >>> Name: $title, new price: $price<br>\n";
                    }
                } else {
                    foreach ($empty_code_products as $product) {
                        if ($product['name'] == $title) {
                            $this->db->query("UPDATE `oc_product` SET `price`=$price WHERE `product_id`=$product[product_id]");
                            echo "Updated >>> Name: $title,  Old price: $product[price], new price: $price<br>\n";
                            break;
                        }
                    }
                }
            }
        }
    }
}