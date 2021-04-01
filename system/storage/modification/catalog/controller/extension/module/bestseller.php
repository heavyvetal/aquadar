<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {

			static $module = 0;
			

			if ($this->registry->has('oct_mobiledetect')) {
		        if ($this->oct_mobiledetect->isMobile() && !$this->oct_mobiledetect->isTablet()) {
		            $data['oct_isMobile'] = $this->oct_mobiledetect->isMobile();
		        }

		        if ($this->oct_mobiledetect->isTablet()) {
		            $data['oct_isTablet'] = $this->oct_mobiledetect->isTablet();
		        }
		    }
			

            $data['oct_lazyload'] = false;

            $this->load->model('tool/image');

            $data['oct_lazy_image'] = $this->model_tool_image->resize($this->config->get('theme_oct_feelmart_lazyload_image') ? $this->config->get('theme_oct_feelmart_lazyload_image') : 'catalog/1lazy/lazy-image.svg', 30, 30);

			if ($this->registry->has('oct_mobiledetect')) {
		        if ($this->oct_mobiledetect->isMobile() && !$this->oct_mobiledetect->isTablet() && $this->config->get('theme_oct_feelmart_lazyload_mobile')) {
		            $data['oct_lazyload'] = true;
		        } elseif ($this->oct_mobiledetect->isTablet() && $this->config->get('theme_oct_feelmart_lazyload_tablet')) {
                    $data['oct_lazyload'] = true;
                } elseif ($this->config->get('theme_oct_feelmart_lazyload_desktop')) {
                    $data['oct_lazyload'] = true;
                }
		    } elseif ($this->config->get('theme_oct_feelmart_lazyload_desktop')) {
                $data['oct_lazyload'] = true;
            }
			

			static $module = 0;
			
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');

			$data['position'] = isset($setting['position']) ? $setting['position'] : '';
			

		$this->load->model('tool/image');


			$data['oct_popup_view_status'] = $this->config->get('oct_popup_view_status');
			

			$limit = (isset($setting['limit']) && !empty($setting['limit'])) ? explode('/', $setting['limit']) : explode('/', '10/6/6');

			if (count($limit) == 1) {
	            $limit = explode('/', '10/6/6');
	        }

	        if (isset($data['oct_isMobile'])) {
	            $setting['limit'] = (isset($limit[2]) && !empty($limit[2])) ? trim($limit[2]) : trim($setting['limit']);
	        } elseif (isset($data['oct_isTablet'])) {
	            $setting['limit'] = (isset($limit[1]) && !empty($limit[1])) ? trim($limit[1]) : trim($setting['limit']);
	        } else {
	            $setting['limit'] = (isset($limit[0]) && !empty($limit[0])) ? trim($limit[0]) : trim($setting['limit']);
	        }
			
		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

		if ($results) {

			$oct_product_stickers = [];
			$data['sticker_colors'] = [];

			if ($this->config->get('oct_stickers_status')) {
				$oct_stickers = $this->config->get('oct_stickers_data');

				$data['oct_sticker_you_save'] = false;

				if ($oct_stickers) {
					$data['oct_sticker_you_save'] = isset($oct_stickers['stickers']['special']['persent']) ? true : false;
				}

				$this->load->model('octemplates/stickers/oct_stickers');
			}
			
			foreach ($results as $result) {

			if (isset($oct_stickers) && $oct_stickers) {
				$oct_stickers_data = $this->model_octemplates_stickers_oct_stickers->getOCTStickers($result);

				$oct_product_stickers = [];

				if ($oct_stickers_data) {
					$oct_product_stickers = $oct_stickers_data['stickers'];
					$data['sticker_colors'][] = $oct_stickers_data['sticker_colors'];
				}
			}
			
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}


			if ($result['quantity'] <= 0) {
				$stock = $result['stock_status'];
			} else {
				$stock = false;
			}

			$can_buy = true;

			if ($result['quantity'] <= 0 && !$this->config->get('config_stock_checkout')) {
				$can_buy = false;
			} elseif ($result['quantity'] <= 0 && $this->config->get('config_stock_checkout')) {
				$can_buy = true;
			}
			
				$data['products'][] = array(
					'product_id'  => $result['product_id'],

			'oct_stickers'  => $oct_product_stickers,
			'you_save'		=> $result['you_save'],
			
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,

			'stock'     => $stock,
			'can_buy'   => $can_buy,
			
					'tax'         => $tax,
					'rating'      => $rating,

			'reviews'	  => isset($product_info['reviews']) ? $product_info['reviews'] : $result['reviews'],
			

			'reviews'	  => $result['reviews'],
			
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


			$data['module'] = $module++;
			

			if (isset($data['sticker_colors']) && $data['sticker_colors']) {
				$oct_color_stickers = [];

				foreach ($data['sticker_colors'] as $sticker_colors) {
					foreach ($sticker_colors as $key=>$sticker_color) {
						$oct_color_stickers[$key] = $sticker_color;
					}
				}

				$data['sticker_colors'] = $oct_color_stickers;
			}
			

            $data['module_name'] = mb_strtolower(str_replace('ControllerExtensionModule', '', get_class($this)));
			$data['module'] = $module++;

			return $this->load->view('octemplates/module/oct_products_modules', $data);
			
			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
