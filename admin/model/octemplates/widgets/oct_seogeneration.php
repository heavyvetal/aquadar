<?php
/**********************************************************/
/*	@copyright	OCTemplates 2015-2019.					  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**********************************************************/

class ModelOCTemplatesWidgetsOCTSeoGeneration extends Model {
	public function seoUrlGenerator($type, $language_id, $store_id, $data, $element_id) {
		$oct_seo_url_data = $this->config->get('theme_oct_feelmart_seo_url_data');
		
		if (!empty($oct_seo_url_data[$type])) {
			$oct_urlgen_template = $oct_seo_url_data[$type];
			
			$i = 0;
			
			switch ($type) {
				case 'product':
					$name = (isset($data['product_description'][$language_id]['name']) && !empty($data['product_description'][$language_id]['name'])) ? strip_tags(html_entity_decode($data['product_description'][$language_id]['name'], ENT_QUOTES, 'UTF-8')) : '';
					$model = (isset($data['model']) && !empty($data['model'])) ? strip_tags(html_entity_decode($data['model'], ENT_QUOTES, 'UTF-8')) : '';
					$sku = (isset($data['sku']) && !empty($data['sku'])) ? strip_tags(html_entity_decode($data['sku'], ENT_QUOTES, 'UTF-8')) : '';
					$brand = (isset($data['manufacturer']) && !empty($data['manufacturer'])) ? strip_tags(html_entity_decode($data['manufacturer'], ENT_QUOTES, 'UTF-8')) : '';
					break;
				
				case 'category':
					$name = (isset($data['category_description'][$language_id]['name']) && !empty($data['category_description'][$language_id]['name'])) ? strip_tags(html_entity_decode($data['category_description'][$language_id]['name'], ENT_QUOTES, 'UTF-8')) : '';
					$model = '';
					$sku = '';
					$brand = '';
					break;
					
				case 'manufacturer':
					$name = ($data['name'] && !empty($data['name'])) ? strip_tags(html_entity_decode($this->db->escape($data['name']), ENT_QUOTES, 'UTF-8')) : '';
					$model = '';
					$sku = '';
					$brand = '';
					break;
					
				case 'information':
					$name = (isset($data['information_description'][$language_id]['title']) && !empty($data['information_description'][$language_id]['title'])) ? strip_tags(html_entity_decode($data['information_description'][$language_id]['title'], ENT_QUOTES, 'UTF-8')) : '';					$model = '';
					$sku = '';
					$brand = '';
					break;
					
				case 'blog_category':
					$name = (isset($data['category_description'][$language_id]['name']) && !empty($data['category_description'][$language_id]['name'])) ? strip_tags(html_entity_decode($data['category_description'][$language_id]['name'], ENT_QUOTES, 'UTF-8')) : '';
					$model = '';
					$sku = '';
					$brand = '';
					break;
					
				case 'blog_article':
					$name = (isset($data['article_description'][$language_id]['name']) && !empty($data['article_description'][$language_id]['name'])) ? strip_tags(html_entity_decode($data['article_description'][$language_id]['name'], ENT_QUOTES, 'UTF-8')) : '';
					$model = '';
					$sku = '';
					$brand = '';
					break;
					
				default:
					$name = '';
					$model = '';
					$sku = '';
					$brand = '';
					break;
			}
			
			if (!empty($name)) {
				$oct_replace = [
					'[name]' => $name,
					'[model]' => $model,
					'[sku]'	=> $sku,
					'[brand]'	=> $brand,
					'[lang_prefix]' => isset($oct_seo_url_data['lang_prefix'][$language_id]) ? $oct_seo_url_data['lang_prefix'][$language_id] : ''
				];
				
				$keyword = str_replace(array_keys($oct_replace), array_values($oct_replace), $oct_urlgen_template);
				
				$oct_keyword_translit = $this->octTranslit($this->db->escape($keyword));
				
				$unic = false;
				
				while ($unic === false) {
					if ($i > 0) {
						$oct_keyword_translit = $oct_keyword_translit . '-'. $i;
					}
					
					$sql = $this->db->query("SELECT seo_url_id FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)$store_id . "' AND keyword = '" . $this->db->escape($oct_keyword_translit) . "'");
					
					if (!$sql->num_rows) {
						$unic = true;
					} else {
						$unic = false;
						
						$i++;
					}
				}
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = '". str_replace('_', '', $type) ."_id=" . (int)$element_id . "', keyword = '" . $this->db->escape($oct_keyword_translit) . "'");
			}
		}
	}
	
	public function octTranslit($string) {
		$string = (string)$string;
		$string = strip_tags($string);
		$string = str_replace(array("\n", "\r"), " ", $string);
		$string = preg_replace("/\s+/", ' ', $string);
		$string = trim($string);
		$string = utf8_strtolower($string);
		
		$lang_tr = [
			'??' => 'a', '??' => 'A', '??' => 'a', '??' => 'A',
			'??' => 'a', '??' => 'A', '??' => 'a', '??' => 'A',
			'??' => 'a', '??' => 'A', '??' => 'a', '??' => 'A',
			'??' => 'a', '??' => 'A', '??' => 'a', '??' => 'A',
			'??' => 'ae', '??' => 'AE', '??' => 'ae', '??' => 'AE',
			'???' => 'b', '???' => 'B', '??' => 'c', '??' => 'C',
			'??' => 'c', '??' => 'C', '??' => 'c', '??' => 'C',
			'??' => 'c', '??' => 'C', '??' => 'c', '??' => 'C',
			'??' => 'd', '??' => 'D', '???' => 'd', '???' => 'D',
			'??' => 'd', '??' => 'D', '??' => 'dh', '??' => 'Dh',
			'??' => 'e', '??' => 'E', '??' => 'e', '??' => 'E',
			'??' => 'e', '??' => 'E', '??' => 'e', '??' => 'E',
			'??' => 'e', '??' => 'E', '??' => 'e', '??' => 'E',
			'??' => 'e', '??' => 'E', '??' => 'e', '??' => 'E',
			'??' => 'e', '??' => 'E', '???' => 'f', '???' => 'F',
			'??' => 'f', '??' => 'F', '??' => 'g', '??' => 'G',
			'??' => 'g', '??' => 'G', '??' => 'g', '??' => 'G',
			'??' => 'g', '??' => 'G', '??' => 'h', '??' => 'H',
			'??' => 'h', '??' => 'H', '??' => 'i', '??' => 'I',
			'??' => 'i', '??' => 'I', '??' => 'i', '??' => 'I',
			'??' => 'i', '??' => 'I', '??' => 'i', '??' => 'I',
			'??' => 'i', '??' => 'I', '??' => 'i', '??' => 'I',
			'??' => 'j', '??' => 'J', '??' => 'k', '??' => 'K',
			'??' => 'l', '??' => 'L', '??' => 'l', '??' => 'L',
			'??' => 'l', '??' => 'L', '??' => 'l', '??' => 'L',
			'???' => 'm', '???' => 'M', '??' => 'n', '??' => 'N',
			'??' => 'n', '??' => 'N', '??' => 'n', '??' => 'N',
			'??' => 'n', '??' => 'N', '??' => 'o', '??' => 'O',
			'??' => 'o', '??' => 'O', '??' => 'o', '??' => 'O',
			'??' => 'o', '??' => 'O', '??' => 'o', '??' => 'O',
			'??' => 'oe', '??' => 'OE', '??' => 'o', '??' => 'O',
			'??' => 'o', '??' => 'O', '??' => 'oe', '??' => 'OE',
			'???' => 'p', '???' => 'P', '??' => 'r', '??' => 'R',
			'??' => 'r', '??' => 'R', '??' => 'r', '??' => 'R',
			'??' => 's', '??' => 'S', '??' => 's', '??' => 'S',
			'??' => 's', '??' => 'S', '???' => 's', '???' => 'S',
			'??' => 's', '??' => 'S', '??' => 's', '??' => 'S',
			'??' => 'SS', '??' => 't', '??' => 'T', '???' => 't',
			'???' => 'T', '??' => 't', '??' => 'T', '??' => 't',
			'??' => 'T', '??' => 't', '??' => 'T', '??' => 'u',
			'??' => 'U', '??' => 'u', '??' => 'U', '??' => 'u',
			'??' => 'U', '??' => 'u', '??' => 'U', '??' => 'u',
			'??' => 'U', '??' => 'u', '??' => 'U', '??' => 'u',
			'??' => 'U', '??' => 'u', '??' => 'U', '??' => 'u',
			'??' => 'U', '??' => 'u', '??' => 'U', '??' => 'ue',
			'??' => 'UE', '???' => 'w', '???' => 'W', '???' => 'w',
			'???' => 'W', '??' => 'w', '??' => 'W', '???' => 'w',
			'???' => 'W', '??' => 'y', '??' => 'Y', '???' => 'y',
			'???' => 'Y', '??' => 'y', '??' => 'Y', '??' => 'y',
			'??' => 'Y', '??' => 'z', '??' => 'Z', '??' => 'z',
			'??' => 'Z', '??' => 'z', '??' => 'Z', '??' => 'th',
			'??' => 'Th', '??' => 'u', '??' => 'a', '??' => 'a',
			'??' => 'b', '??' => 'b', '??' => 'v', '??' => 'v',
			'??' => 'g', '??' => 'g', '??' => 'd', '??' => 'd',
			'??' => 'e', '??' => 'E', '??' => 'e', '??' => 'E',
			'??' => 'zh', '??' => 'zh', '??' => 'z', '??' => 'z',
			'??' => 'i', '??' => 'i', '??' => 'j', '??' => 'j',
			'??' => 'k', '??' => 'k', '??' => 'l', '??' => 'l',
			'??' => 'm', '??' => 'm', '??' => 'n', '??' => 'n',
			'??' => 'o', '??' => 'o', '??' => 'p', '??' => 'p',
			'??' => 'r', '??' => 'r', '??' => 's', '??' => 's',
			'??' => 't', '??' => 't', '??' => 'u', '??' => 'u',
			'??' => 'f', '??' => 'f', '??' => 'h', '??' => 'h',
			'??' => 'c', '??' => 'c', '??' => 'ch', '??' => 'ch',
			'??' => 'sh', '??' => 'sh', '??' => 'sch', '??' => 'sch',
			'??' => '', '??' => '', '??' => 'y', '??' => 'y',
			'??' => '', '??' => '', '??' => 'e', '??' => 'e',
			'??' => 'ju', '??' => 'ju', '??' => 'ja', '??' => 'ja',
			'??'=>'i', '??'=>'ji'
		];
		
		$string = strtr($string, $lang_tr);
		$string = preg_replace("/[^0-9a-z-_ ]/i", "", $string);
		$string = str_replace(" ", "-", $string);
		$string = str_replace("----", "-", $string);
		$string = str_replace("---", "-", $string);
		$string = str_replace("--", "-", $string);
		$string = trim($string);
		$string = trim($string, '-');
		
		return $string;
	}
}