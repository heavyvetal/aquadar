<?php
class ControllerProductCategory extends Controller {

			public function octAllCategories() {
				//$this->load->language('octemplates/product/octallcategories');

				$this->load->model('catalog/category');
				$this->load->model('catalog/product');
				$this->load->model('tool/image');

				$data['breadcrumbs'] = [];

				$data['breadcrumbs'][] = [
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home')
				];

				$data['breadcrumbs'][] = [
					'text' => $this->language->get('text_oct_all_categories'),
					'href' => $this->url->link('octemplates/product/octallcategories', '', true)
				];

				$oct_data['breadcrumbs'] = $data['breadcrumbs'];

				$data['oct_breadcrumbs'] = $this->load->controller('common/header/octBreadcrumbs', $oct_data);

				$this->document->setTitle($this->language->get('text_oct_all_categories'));
				//$this->document->setDescription($category_info['meta_description']);
				//$this->document->setKeywords($category_info['meta_keyword']);

				$data['categories'] = [];

				if(isset($this->request->server['HTTP_ACCEPT']) && strpos($this->request->server['HTTP_ACCEPT'], 'webp')) {
					$oct_webP = 1 . '-' . $this->session->data['currency'];
				} else {
					$oct_webP = 0 . '-' . $this->session->data['currency'];
				}

				$result_all_categories = $this->cache->get('octemplates.all_categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . $oct_webP);

				if (!$result_all_categories) {
					foreach ($this->model_catalog_category->getCategories() as $category) {
						$filter_data_main = [
							'filter_category_id' => $category['category_id'],
							'filter_sub_category' => true
						];

				        // Level 2
				        $children_data = [];

				        $children = $this->model_catalog_category->getCategories($category['category_id']);

				        foreach ($children as $child) {
				            $filter_data = array(
				                'filter_category_id' => $child['category_id'],
				                'filter_sub_category' => true
				            );

				            // Level 3
				            $children_data_2 = [];
				            $children_2      = $this->model_catalog_category->getCategories($child['category_id']);

				            foreach ($children_2 as $child_2) {
				                $filter_data2 = [
				                    'filter_category_id' => $child_2['category_id'],
				                    'filter_sub_category' => true
				                ];

				                /*
				                $children_3 = $this->model_catalog_category->getCategories($child_2['category_id']);

				                $children_data_3 = [];

				                foreach ($children_3 as $child_3) {
					                $filter_data3 = [
						                'filter_category_id'  => $child_3['category_id'],
						                'filter_sub_category' => true
					                ];

					                $children_data_3[] = [
						                'category_id' => $child_3['category_id'],
						                'count_products' => ($this->config->get('config_product_count') ? $this->model_catalog_product->getTotalProducts($filter_data3) : ''),
						                'name'  => $child_3['name'],
						                'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_2['category_id'] . '_' . $child_3['category_id'], true)
					                ];
				                }
				                */

				                $children_data_2[] = [
				                    //'children' => $children_data_3,
				                    'category_id' => $child_2['category_id'],
				                    'count_products' => ($this->config->get('config_product_count') ? $this->model_catalog_product->getTotalProducts($filter_data2) : ''),
				                    'name' => $child_2['name'],
				                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_2['category_id'], true)
				                ];
				            }

				            $children_data[] = [
				                'children' => $children_data_2,
				                'count_products' => ($this->config->get('config_product_count') ? $this->model_catalog_product->getTotalProducts($filter_data) : ''),
				                'name' => $child['name'],
				                'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'], true)
				            ];
				        }

				        // Level 1
				        $data['categories'][] = [
				            'name' => $category['name'],
				            'count_products' => ($this->config->get('config_product_count') ? $this->model_catalog_product->getTotalProducts($filter_data_main) : ''),
				            'thumb' => $category['image'] ? $this->model_tool_image->resize($category['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height')) : $this->model_tool_image->resize('no-thumb.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height')),
				            'children' => $children_data,
				            'href' => $this->url->link('product/category', 'path=' . $category['category_id'], true)
				        ];
					}

					$result_all_categories = $data['categories'];

					$this->cache->set('octemplates.all_categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . $oct_webP, $result_all_categories);
				}

				$data['categories'] = $result_all_categories;

				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

				$this->response->setOutput($this->load->view('octemplates/product/oct_all_categories', $data));
			}
			
	public function index() {

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
			

			$data['oct_feelmart_data'] = $oct_feelmart_data = $this->config->get('theme_oct_feelmart_data');

			if (isset($oct_feelmart_data['category_view_sort_oder']) && $oct_feelmart_data['category_view_sort_oder']) {
				$oct_feelmart_sort_data = $this->config->get('theme_oct_feelmart_sort_data');

				if (isset($oct_feelmart_sort_data['deff_sort']) && $oct_feelmart_sort_data['deff_sort']) {
					$sort_order = explode('-', $oct_feelmart_sort_data['deff_sort']);
				}
			}
			
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			
			$sort = (isset($sort_order) && !empty($sort_order) && isset($sort_order[0])) ? $sort_order[0] : 'p.sort_order';
			
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			
			$order = (isset($sort_order) && !empty($sort_order) && isset($sort_order[1])) ? $sort_order[1] : 'ASC';
			
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];


			if ($this->config->get('theme_oct_feelmart_seo_title_status')) {
				$oct_seo_title_data = $this->config->get('theme_oct_feelmart_seo_title_data');

				if ((isset($oct_seo_title_data['category']['title_status']) && $oct_seo_title_data['category']['title_status']) && (isset($oct_seo_title_data['category']['title'][$this->config->get('config_language_id')]) && !empty($oct_seo_title_data['category']['title'][$this->config->get('config_language_id')]))) {
					$oct_address = (isset($oct_feelmart_data['contact_address'][$this->config->get('config_language_id')]) && !empty($oct_feelmart_data['contact_address'][$this->config->get('config_language_id')])) ? str_replace(PHP_EOL, ', ', $oct_feelmart_data['contact_address'][$this->config->get('config_language_id')]) : '';
					$oct_phone = (isset($oct_feelmart_data['contact_telephone']) && !empty($oct_feelmart_data['contact_telephone'])) ? str_replace(PHP_EOL, ', ',  $oct_feelmart_data['contact_telephone']) : '';
					$oct_time = (isset($oct_feelmart_data['contact_open'][$this->config->get('config_language_id')]) && !empty($oct_feelmart_data['contact_open'][$this->config->get('config_language_id')])) ? str_replace(PHP_EOL, ', ', $oct_feelmart_data['contact_open'][$this->config->get('config_language_id')]) : '';

					$oct_replace = [
						'[name]' => strip_tags(html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8')),
						'[address]' => $oct_address,
						'[phone]' => $oct_phone,
						'[time]' => $oct_time,
						'[store]' => $this->config->get('config_name')
					];

					$oct_seo_title = str_replace(array_keys($oct_replace), array_values($oct_replace), $oct_seo_title_data['category']['title'][$this->config->get('config_language_id')]);

					if ((isset($oct_seo_title_data['category']['title_empty']) && $oct_seo_title_data['category']['title_empty']) && empty($category_info['meta_title'])) {
						$og_seo_title = true;
						$this->document->setTitle(htmlspecialchars($oct_seo_title));
					} elseif (!isset($oct_seo_title_data['category']['title_empty'])) {
						$og_seo_title = true;
						$this->document->setTitle(htmlspecialchars($oct_seo_title));
					}
				}

				if ((isset($oct_seo_title_data['category']['description_status']) && $oct_seo_title_data['category']['description_status']) && (isset($oct_seo_title_data['category']['description'][$this->config->get('config_language_id')]) && !empty($oct_seo_title_data['category']['description'][$this->config->get('config_language_id')]))) {
					$oct_address = (isset($oct_feelmart_data['contact_address'][$this->config->get('config_language_id')]) && !empty($oct_feelmart_data['contact_address'][$this->config->get('config_language_id')])) ? str_replace(PHP_EOL, ', ', $oct_feelmart_data['contact_address'][$this->config->get('config_language_id')]) : '';
					$oct_phone = (isset($oct_feelmart_data['contact_telephone']) && !empty($oct_feelmart_data['contact_telephone'])) ? str_replace(PHP_EOL, ', ',  $oct_feelmart_data['contact_telephone']) : '';
					$oct_time = (isset($oct_feelmart_data['contact_open'][$this->config->get('config_language_id')]) && !empty($oct_feelmart_data['contact_open'][$this->config->get('config_language_id')])) ? str_replace(PHP_EOL, ', ', $oct_feelmart_data['contact_open'][$this->config->get('config_language_id')]) : '';

					$oct_replace = [
						'[name]' => strip_tags(html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8')),
						'[address]' => $oct_address,
						'[phone]' => $oct_phone,
						'[time]' => $oct_time,
						'[store]' => $this->config->get('config_name')
					];

					$oct_seo_description = str_replace(array_keys($oct_replace), array_values($oct_replace), $oct_seo_title_data['category']['description'][$this->config->get('config_language_id')]);

					if ((isset($oct_seo_title_data['category']['description_empty']) && $oct_seo_title_data['category']['description_empty']) && empty($category_info['meta_description'])) {
						$og_seo_description = true;
						$this->document->setDescription(htmlspecialchars($oct_seo_description));
					} elseif (!isset($oct_seo_title_data['category']['description_empty'])) {
						$og_seo_description = true;
						$this->document->setDescription(htmlspecialchars($oct_seo_description));
					}
				}
			}
			
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);


			$data['ocfilter_on'] = $this->config->get('module_ocfilter_status') ? 1 : 0;
			
			
			if ($category_info['image'] && (isset($oct_feelmart_data['category_cat_image']) && $oct_feelmart_data['category_cat_image'])) {
			
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

			if (!isset($oct_feelmart_data['category_desc_in_page']) && $page > 1) {
				$data['description'] = false;
				$data['thumb'] = false;
			} else {
				$data['description'] = str_replace("<img", "<img class='img-fluid'", html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8'));
			}
			
			$data['compare'] = $this->url->link('product/compare');

			$data['language_id'] = (int)$this->config->get('config_language_id');
			$data['categories_page'] = (isset($oct_feelmart_data['category_page']) && $oct_feelmart_data['category_page'] && $this->config->get("oct_megamenu_categories_page")) ? unserialize($category_info['page_group_links']) : [];
			

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			if(isset($oct_feelmart_data['category_view_subcats']) && $oct_feelmart_data['category_view_subcats'] == 'on'){
			


			if(isset($this->request->server['HTTP_ACCEPT']) && strpos($this->request->server['HTTP_ACCEPT'], 'webp')) {
				$oct_webP = 1 . '-' . $this->session->data['currency'];
			} else {
				$oct_webP = 0 . '-' . $this->session->data['currency'];
			}

			$oct_categories = $this->cache->get('octemplates.sub_categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$category_id . '.' . $oct_webP);

			if (!$oct_categories) {
			
			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {

			if ($result['image'] && file_exists(DIR_IMAGE.$result['image'])) {
				$cat_image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_height'));
			} else {
				$cat_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_height'));
			}
			
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),

			'image' => $cat_image,
			
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}

				$oct_categories = $data['categories'];

				$this->cache->set('octemplates.sub_categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$category_id . '.' . $oct_webP, $oct_categories);
			}

			$data['categories'] = $oct_categories;
			


			}
			
			$data['products'] = array();

			$data['oct_popup_view_status'] = $this->config->get('oct_popup_view_status');
			

	        $oct_feelmart_data_atributes = $this->config->get('theme_oct_feelmart_data_atributes');
			

			$filter_data = array(
				'filter_category_id' => $category_id,

			'filter_sub_category' => (isset($oct_feelmart_data['category_subcat_products']) && $oct_feelmart_data['category_subcat_products'] == 'on') ? true : false,
			
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

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

			if ($result['image'] && file_exists(DIR_IMAGE.$result['image'])) {
				$cat_image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_height'));
			} else {
				$cat_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_sub_category_height'));
			}
			
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}


			$oct_atributes = false;
				
			if (isset($oct_feelmart_data_atributes) && $oct_feelmart_data_atributes) {
				$limit_attr  = $this->config->get('theme_oct_feelmart_data_cat_atr_limit') ? $this->config->get('theme_oct_feelmart_data_cat_atr_limit') : 5;
				
				$oct_atributes = $this->model_catalog_product->getOctProductAttributes($result['product_id'], $limit_attr);
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
			

			if (isset($oct_stickers) && $oct_stickers) {
				$oct_stickers_data = $this->model_octemplates_stickers_oct_stickers->getOCTStickers($result);

				$oct_product_stickers = [];

				if (isset($oct_stickers_data) && $oct_stickers_data) {
					$oct_product_stickers = $oct_stickers_data['stickers'];
					$data['sticker_colors'][] = $oct_stickers_data['sticker_colors'];
				}
			}
			
				$data['products'][] = array(
					'product_id'  => $result['product_id'],

			'oct_stickers'  => $oct_product_stickers,
			'you_save'	  	=> $result['you_save'],
			
					'thumb'       => $image,

			'oct_atributes'       => $oct_atributes,
			
					'name'        => $result['name'],
					
			'description' => (isset($oct_feelmart_data['category_product_desc']) && $oct_feelmart_data['category_product_desc'] == 'on') ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..' : false,
			
					'price'       => $price,
					'special'     => $special,

			'stock'     => $stock,
			'can_buy'   => $can_buy,
			
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => 
			$this->config->get('config_review_status') ? $result['rating'] : false,
			'oct_model'	  => $this->config->get('theme_oct_feelmart_data_model') ? $result['model'] : '',
			'reviews'	  => $result['reviews'],
			'quantity'	  => $result['quantity'] <= 0 ? true : false,
			
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			if ((isset($oct_feelmart_sort_data) && !empty($oct_feelmart_sort_data)) && (isset($oct_feelmart_sort_data['sort']) && !empty($oct_feelmart_sort_data['sort']))) {
				$data['sorts'] = [];

				foreach ($oct_feelmart_sort_data['sort'] as $oct_sort) {
					$sort_order = explode('-', $oct_sort);

					$sort_name = str_replace(['.','-'], ['_', '_'], $oct_sort);

					if (!$this->config->get('config_review_status') && $sort_order[0] == 'rating') {
						continue;
					}

					$data['sorts'][] = array(
						'text'  => $this->language->get('text_' . $sort_name),
						'value' => $oct_sort,
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=' . $sort_order[0] . '&order='. $sort_order[1] . $url)
					);
				}
			}
			

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


            if (isset($oct_feelmart_data['open_graph']) && $oct_feelmart_data['open_graph']) {
                $site_link = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;

				$config_logo = file_exists(DIR_IMAGE . $this->config->get('config_logo')) ? $this->config->get('config_logo') : 'catalog/opencart-logo.png';

                $oct_ogimage = $category_info['image'] ? $category_info['image'] : $config_logo;
                $category_image = $site_link . 'image/' . $oct_ogimage;

				$image_info = getimagesize(DIR_IMAGE . $oct_ogimage);

				$image_width  = $image_info[0];
				$image_height = $image_info[1];
				$mime_type = isset($image_info['mime']) ? $image_info['mime'] : '';

                $this->document->setOCTOpenGraph('og:title', htmlspecialchars(strip_tags(str_replace("\r", " ", str_replace("\n", " ", str_replace("\\", "/", str_replace("\"", "", (isset($og_seo_title) && $og_seo_title) ? $oct_seo_title : $category_info['meta_title'])))))));
                $this->document->setOCTOpenGraph('og:description', htmlspecialchars(strip_tags(str_replace("\r", " ", str_replace("\n", " ", str_replace("\\", "/", str_replace("\"", "", (isset($og_seo_description) && $og_seo_description) ? $oct_seo_description : $category_info['meta_description'])))))));
                $this->document->setOCTOpenGraph('og:site_name', htmlspecialchars(strip_tags(str_replace("\r", " ", str_replace("\n", " ", str_replace("\\", "/", str_replace("\"", "", $this->config->get('config_name'))))))));
                $this->document->setOCTOpenGraph('og:url', $this->url->link('product/category', 'path=' . $category_info['category_id']));
                $this->document->setOCTOpenGraph('og:image', str_replace(" ", "%20", $category_image));

				if (isset($mime_type) && $mime_type) {
                	$this->document->setOCTOpenGraph('og:image:type', $mime_type);
				}

				if (isset($image_width) && $image_width) {
                	$this->document->setOCTOpenGraph('og:image:width', $image_width);
				}

				if (isset($image_height) && $image_height) {
					$this->document->setOCTOpenGraph('og:image:height', $image_height);
				}

                $this->document->setOCTOpenGraph('og:image:alt', htmlspecialchars(strip_tags(str_replace("\r", " ", str_replace("\n", " ", str_replace("\\", "/", str_replace("\"", "", $data['heading_title'])))))));
                $this->document->setOCTOpenGraph('og:type', 'website');
            }
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}


			if (isset($data['sticker_colors']) && $data['sticker_colors']) {
				$oct_color_stickers = [];

				foreach ($data['sticker_colors'] as $sticker_colors) {
					foreach ($sticker_colors as $key=>$sticker_color) {
						$oct_color_stickers[$key] = $sticker_color;
					}
				}

				$data['sticker_colors'] = $oct_color_stickers;
			}
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$oct_data['breadcrumbs'] = $data['breadcrumbs'];

			$data['oct_breadcrumbs'] = $this->load->controller('common/header/octBreadcrumbs', $oct_data);
			

			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');


	        $oct_404_page_status = $this->config->get('oct_404_page_status');

	        if ($oct_404_page_status) {
		        $oct_404_page_data = $this->config->get('oct_404_page_data');

	            if (isset($oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['title']) && !empty($oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['title'])) {
	                $data['heading_title'] = $oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['title'];
	                $this->document->setTitle($data['heading_title']);
	            }

				$data['oct_404_image'] = '';

	            if (isset($oct_404_page_data['image']) && !empty($oct_404_page_data['image'])) {
	                if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
	        			$data['oct_404_image'] = $this->config->get('config_ssl') . 'image/' . $oct_404_page_data['image'];
	        		} else {
	        			$data['oct_404_image'] = $this->config->get('config_url') . 'image/' . $oct_404_page_data['image'];
	        		}
	            }

	            if (isset($oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['text']) && !empty($oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['text'])) {
	            	$data['text_error'] = html_entity_decode($oct_404_page_data['module_text'][(int)$this->config->get('config_language_id')]['text'], ENT_QUOTES, 'UTF-8');
				}
	        }
			
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$oct_data['breadcrumbs'] = $data['breadcrumbs'];

			$data['oct_breadcrumbs'] = $this->load->controller('common/header/octBreadcrumbs', $oct_data);
			

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
