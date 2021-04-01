<?php
/**************************************************************/
/*	@copyright	OCTemplates 2016-2019						  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctSlideshowPlus extends Controller {
	public function index($setting) {
		static $module = 0;
		
		$this->load->model('octemplates/module/oct_slideshow_plus');
		$this->load->model('tool/image');
		
		$this->document->addScript('catalog/view/theme/oct_feelmart/js/slick/slick.min.js');
		$this->document->addStyle('catalog/view/theme/oct_feelmart/js/slick/slick.min.css');
		
		$data['oct_slideshows_plus'] = [];
		
		$results = $this->model_octemplates_module_oct_slideshow_plus->getSlideshow($setting['slideshow_id']);
		
		foreach ($results as $result) {
			if (is_file(DIR_IMAGE.$result['image'])) {

				$data['status_additional_banners']	= $result['status_additional_banners'];
				$data['position_banners']			= $result['position_banners'];
					
				$data['oct_slideshows_plus'][] = [
					'title'                  => $result['title'],
					'button'                 => $result['button'],
					'link'                   => ($result['link'] == '#' or empty($result['link'])) ? 'javascript:;' : $result['link'],
					'background_color'       => $result['background_color'],
					'title_color'            => $result['title_color'],
					'text_color'             => $result['text_color'],
					'button_color'           => $result['button_color'],
					'button_background'      => $result['button_background'],
					'button_color_hover'     => $result['button_color_hover'],
					'button_background_hover' => $result['button_background_hover'],
					'description'            => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'image'                  => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				];
			}
		}
		
		$products_data = $this->model_octemplates_module_oct_slideshow_plus->getSlideshowProduct($setting['slideshow_id']);
		
		if ($products_data) {
			$this->load->model('catalog/product');

			$data['position'] = isset($setting['position']) ? $setting['position'] : '';
			
			
			foreach ($products_data as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['dop_width'], $setting['dop_height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['dop_width'], $setting['dop_height']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$product_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$product_price = false;
					}
					
					if ((float) $product_info['special']) {
						$ptoduct_special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$ptoduct_special = false;
					}
					
					$can_buy = true;

					if ($product_info['quantity'] <= 0 && !$this->config->get('config_stock_checkout')) {
						$can_buy = false;
					} elseif ($product_info['quantity'] <= 0 && $this->config->get('config_stock_checkout')) {
						$can_buy = true;
					}
					
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
					
					$data['products'][] = [
						'you_save'		=> $product_info['you_save'],
						'can_buy'   => $can_buy,
						'tax'         => $tax,
						'rating'      => $rating,
						'reviews'	  => $product_info['reviews'],
						'product_id' => $product_info['product_id'],
						'sort_order' => $product_info['sort_order'],
						'thumb' => $image,
						'name' => $product_info['name'],
						'price' => $product_price,
						'special' => $ptoduct_special,
						'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					];
				}
			}
		}

		$data['module'] = $module++;
		
		$data['slideshow_id']                         = $setting['slideshow_id'];
		
		return $this->load->view('octemplates/module/oct_slideshow_plus', $data);
	}
}