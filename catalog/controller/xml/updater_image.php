<?php

class ControllerXmlUpdaterImage extends Controller {
    public function index()
    {
        $xml_string = file_get_contents("https://ecosoft.ua/aquadar_ua.xml");
        $xml = new SimpleXMLElement($xml_string);
        $path = $_SERVER['DOCUMENT_ROOT'].'/image/catalog/products_additional/';

        // Индекс начального товара
        $counter = 0;
        // Количество картинок
        $num = 700;
        $end_num = $counter + $num;

        while ($counter < count($xml->item)) {

            if ($counter < $end_num) {
                $obj = $xml->item[$counter];
                $code = $obj->code;
                $title = $obj->title;
                $price = $obj->price;
                $images = (array)$obj->images->image;


                // Убираем первые картинки, т.к. они уже есть
                array_shift($images);

                // Ищем в базе товары с заполненным sku
                if ($code != '') {
                    $products = $this->getProduct($title);
                    //print_r($products);

                    // такой артикул в бд найден
                    if ($products) {
                        // в базе есть дубли с одинаковыми названиями товаров
                        // пишем ссылки в дубль тоже
                        foreach ($products as $product) {
                            $product_id = $product['product_id'];
                            $path_dir = $path.$code;

                            echo "$counter :: $code :: $title >> ".$product_id."<br>\n";

                            // создаем папку картинок товара
                            if (!file_exists($path_dir)) {
                                mkdir($path_dir, 0777);
                            }

                            if (!file_exists($path_dir)) {
                                echo "<span style='color:red;'> Папка не была создана: $path_dir </span>";
                            }

                            $sort_order = 10;

                            foreach ($images as $image) {
                                $image_link = $image;
                                $image_link_parts = explode('/', $image_link);
                                $image_link_end = end($image_link_parts);
                                $full_name_dir = $path_dir.'/'.$image_link_end;
                                $link_in_db = 'catalog/products_additional/'.$code.'/'.$image_link_end;
                                //echo ">>>$link_in_db sort_order=$sort_order ";

                                if (!$this->isInDb($link_in_db, $product_id)) {
                                    echo "Будет записана<br>\n";
                                    $this->insertIntoDb($product_id, $link_in_db, $sort_order);
                                } else {
                                    echo "Уже в базе<br>\n";
                                }

                                // Проеверяем, есть ли уже такое изображение в папке
                                if ($image_link_end != '' && file_exists($full_name_dir)) {
                                    echo "такая уже есть - ".$full_name_dir."<br>\n";
                                } else {
                                    echo "такой нет - ".$full_name_dir."<br>\n";
                                    file_put_contents($full_name_dir, file_get_contents($image_link));
                                }

                                $sort_order += 10;
                            }

                        }

                    }
                }
                $counter++;
            } else {
                break;
            }
        }
    }

    /**
     * @param string $title
     * @return array
     */
    private function getProduct($title)
    {
        $product = $this->db->query("
            SELECT DISTINCT pd.product_id
            FROM `oc_product_description` pd 
            WHERE pd.name='" . $this->db->escape($title) . "'
        ")->rows;
        
        return $product;
    }

    private function isInDb($link_in_db, $product_id)
    {
        $link_from_db = $this->db->query("
            SELECT `product_id` 
            FROM `oc_product_image` 
            WHERE `image` = '".$link_in_db."'
            AND `product_id` = $product_id
            LIMIT 1
        ")->rows;

        if ($link_from_db) $link_from_db = $link_from_db[0];

        if ($link_from_db['product_id'] == $product_id) {
            return true;
        } else {
            return false;
        }
    }

    private function insertIntoDb($product_id, $image, $sort_order)
    {
        $res = $this->db->query("
            INSERT INTO `oc_product_image`(`product_id`, `image`, `sort_order`) 
            VALUES ($product_id, '".$image."' , $sort_order)
        ");
    }
}