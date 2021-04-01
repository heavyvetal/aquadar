<?php
/********************************************************/
/*	@copyright	OCTemplates 2019-2020					*/
/*	@support	https://octemplates.net/				*/
/*	@license	LICENSE.txt								*/
/********************************************************/

class ControllerOCTemplatesModuleOctMegamenu extends Controller {
    public function index($data = []) {
        if ($this->config->get('oct_megamenu_status')) {
	        $this->load->language('octemplates/module/oct_megamenu');

	        $this->load->model('octemplates/module/oct_megamenu');
	        $this->load->model('tool/image');

	        if (isset($data['mobile']) && $data['mobile']) {
		        if ($this->config->get('oct_megamenu_mobile_st_categories')) {
			        $data['standart_menu'] = $this->load->controller('common/menu', $data);
		        }

		        if ($this->config->get('oct_megamenu_mobile_categories')) {
			        $data['oct_megamenu_mobile_categories'] = true;
		        }

		        if ($this->config->get('oct_megamenu_brands')) {
			        $data['oct_megamenu_brands'] = true;
		        }

		        if ($this->config->get('oct_megamenu_links')) {
			        $data['oct_megamenu_links'] = true;
		        }

		        if ($this->config->get('oct_megamenu_blog')) {
			        $data['oct_megamenu_blog'] = true;
		        }
			} elseif ($this->config->get('oct_megamenu_categories')) {
				$data['standart_menu'] = $this->load->controller('common/menu');
			}

	        if(isset($this->request->server['HTTP_ACCEPT']) && strpos($this->request->server['HTTP_ACCEPT'], 'webp')) {
				$oct_webP = 1 . '-' . $this->session->data['currency'];
			} else {
				$oct_webP = 0 . '-' . $this->session->data['currency'];
			}

	        $cat_title = $this->config->get('oct_megamenu_title');

			$data['text_category'] = (isset($cat_title[(int)$this->config->get('config_language_id')]) && $cat_title[(int)$this->config->get('config_language_id')]) ? $cat_title[(int)$this->config->get('config_language_id')] : $this->language->get('text_category');

	        $data['items'] = $this->cache->get('octemplates.megamenu.' . (int)$this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . $oct_webP);

	        if (isset($data['items']) && empty($data['items'])) {
	            $results = $this->model_octemplates_module_oct_megamenu->getMegamenus();

	            foreach ($results as $result) {
	                if ($result['image']) {
	                    $image = $this->model_tool_image->resize($result['image'], 35, 35);
	                } else {
	                    $image = false;
	                }

	                $childrens = [];

	                if ($result['item_type'] == 2) {
	                    $children_data = $this->model_octemplates_module_oct_megamenu->getMegamenuCategory($result['megamenu_id']);

	                    if ($children_data) {
		                    $this->load->model('catalog/category');

		                    foreach ($children_data as $category_id) {
		                        $category_info = $this->model_catalog_category->getCategory($category_id);

		                        if ($category_info) {
		                            if ($category_info['image'] && is_file(DIR_IMAGE . $category_info['image'])) {
		                                $category_image = $this->model_tool_image->resize($category_info['image'], $result['img_width'], $result['img_height']);
		                            } else {
		                                $category_image = $this->model_tool_image->resize('no-thumb.png', $result['img_width'], $result['img_height']);
		                            }

		                            $sub_categories = [];

		                            if ($result['sub_categories']) {
                                        if (isset($data['mobile']) && $data['mobile']) {
                                            $result['limit_item'] = 1000;
                                        }

		                                $category_children = $this->model_catalog_category->getOCTCategories($category_id, $result['limit_item']);

		                                foreach ($category_children as $child) {
		                                    $sub_categories[] = [
		                                        'name' => $child['name'],
		                                        'href' => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
		                                    ];
		                                }
		                            }

		                            $childrens[] = [
		                                'category_id' => $category_info['category_id'],
		                                'thumb' => $result['show_img'] ? $category_image : false,
		                                'name' => $category_info['name'],
		                                'children' => $sub_categories,
										'sort_order' => $category_info['sort_order'],
		                                'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'])
		                            ];
		                        }
		                    }

		                    $i_sort_order = [];

		                    foreach ($childrens as $key => $value) {
		                        $i_sort_order[$key] = $value['sort_order'];
		                    }

		                    array_multisort($i_sort_order, SORT_ASC, $childrens);
	                    }
	                }

	                if ($result['item_type'] == 3) {
	                    $children_data = $this->model_octemplates_module_oct_megamenu->getMegamenuManufacturer($result['megamenu_id']);

	                    if ($children_data) {
		                    $this->load->model('catalog/manufacturer');

		                    foreach ($children_data as $manufacturer_id) {
		                        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		                        if ($manufacturer_info) {
		                            if ($manufacturer_info['image'] && is_file(DIR_IMAGE . $manufacturer_info['image'])) {
		                                $manufacturer_image = $this->model_tool_image->resize($manufacturer_info['image'], $result['img_width'], $result['img_height']);
		                            } else {
		                                $manufacturer_image = $this->model_tool_image->resize('no-thumb.png', $result['img_width'], $result['img_height']);
		                            }

		                            $childrens[] = [
		                                'manufacturer_id' => $manufacturer_info['manufacturer_id'],
		                                'sort_order' => $manufacturer_info['sort_order'],
		                                'thumb' => ($result['show_img']) ? $manufacturer_image : false,
		                                'name' => $manufacturer_info['name'],
		                                'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
		                            ];
		                        }
		                    }

                            $i_sort_order = [];

		                    foreach ($childrens as $key => $value) {
		                        $i_sort_order[$key] = $value['name'];
		                    }

		                    array_multisort($i_sort_order, SORT_ASC, SORT_STRING, $childrens);
	                    }
	                }

	                if ($result['item_type'] == 8 && $this->config->get('oct_blogsettings_status')) {
	                    $children_data = $this->model_octemplates_module_oct_megamenu->getMegamenuBlogCategory($result['megamenu_id']);

	                    if ($children_data) {
		                    $this->load->model('octemplates/blog/oct_blogcategory');

		                    foreach ($children_data as $blogcategory_id) {
		                        $blog_info = $this->model_octemplates_blog_oct_blogcategory->getBlogCategory($blogcategory_id);

		                        if ($blog_info) {
		                            $childrens[] = [
		                                'href' => $this->url->link('octemplates/blog/oct_blogcategory', 'blog_path=' . $blogcategory_id),
		                                'name' => $blog_info['name'],
		                                'sort_order' => $blog_info['sort_order']
		                            ];
		                        }
		                    }

		                    $i_sort_order = array();

		                    foreach ($childrens as $key => $value) {
		                        $i_sort_order[$key] = $value['sort_order'];
		                    }

		                    array_multisort($i_sort_order, SORT_ASC, $childrens);
	                    }
	                }

	                $data['items'][] = [
	                    'megamenu_id' => $result['megamenu_id'],
	                    'title' => $result['title'],
	                    'image' => $image,
	                    'href' => ($result['link'] == "#" || empty($result['link'])) ? "javascript:void(0);" : $result['link'],
	                    'open_link_type' => $result['open_link_type'],
	                    'description' => false,
	                    'custom_html' => $result['custom_html'] ? html_entity_decode($result['custom_html'], ENT_QUOTES, 'UTF-8') : '',
	                    'display_type' => $result['display_type'],
	                    'limit_item' => $result['limit_item'],
	                    'show_img' => $result['show_img'],
	                    'children' => $childrens,
	                    'item_type' => $result['item_type']
	                ];
	            }

	            $this->cache->set('octemplates.megamenu.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . $oct_webP, $data['items']);
	        }

	        $menu_template = (isset($data['mobile']) && $data['mobile']) ? 'octemplates/menu/components/oct_mobile_categories' : 'octemplates/menu/oct_megamenu';

	        return $this->load->view($menu_template, $data);
        } elseif(isset($data['mobile']) && $data['mobile']) {
	        $data['standart_menu'] = $this->load->controller('common/menu', $data);

	        return $this->load->view('octemplates/menu/components/oct_mobile_categories', $data);
        }
    }

    public function mobileSideBar($status = 0) {
	    if ($status) {
		    $data['oct_popup_cart_status'] = $this->config->get('theme_oct_feelmart_popup_cart_status');

			$oct_feelmart_data = $this->config->get('theme_oct_feelmart_data');

			$data['isLogged'] = !$this->customer->isLogged() ? false : true;

			$data['cart_link'] = $this->url->link('checkout/cart');
			$data['checkout_link'] = $this->url->link('checkout/checkout', '', true);
			$data['total_products'] = $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0);

			$data['wishlist_link'] = $this->url->link('account/wishlist','', true);

			if ($this->customer->isLogged()) {
				$this->load->model('account/wishlist');

				$data['wishlist_total'] = $this->model_account_wishlist->getTotalWishlist();
			} else {
				$data['wishlist_total'] = (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0);
			}

			$data['compare_link'] = $this->url->link('product/compare','', true);
			$data['compare_total'] = (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0);

			$product_views = [];

			if (isset($this->request->cookie['oct_product_views'])) {
	            $product_views = explode(',', $this->request->cookie['oct_product_views']);
	        } elseif (isset($this->session->data['oct_product_views'])) {
	            $product_views = $this->session->data['oct_product_views'];
	        }

	        if (isset($this->request->cookie['viewed'])) {
	            $product_views = array_merge($product_views, explode(',', $this->request->cookie['viewed']));
	        } elseif (isset($this->session->data['viewed'])) {
	            $product_views = array_merge($product_views, $this->session->data['viewed']);
	        }

			$data['product_views_count'] = count($product_views);

			$data['sidebar_position'] = (isset($oct_feelmart_data['mobile_sidebar_position']) && !empty($oct_feelmart_data['mobile_sidebar_position'])) ? $oct_feelmart_data['mobile_sidebar_position'] : 'bottom';

			return $this->load->view('octemplates/menu/oct_sidebar_mobile', $data);
		} else {
			$this->response->redirect($this->url->link('error/not_found', '', true));
		}
    }

    public function mobileMenu() {
	    if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		    $this->load->language('octemplates/module/oct_megamenu');

		    $data['oct_feelmart_data'] = $oct_feelmart_data = $this->config->get('theme_oct_feelmart_data');

		    $cat_title = $this->config->get('oct_megamenu_mobile_title');

		    $data['oct_menu_catalog'] = (isset($cat_title[(int)$this->config->get('config_language_id')]) && $cat_title[(int)$this->config->get('config_language_id')]) ? $cat_title[(int)$this->config->get('config_language_id')] : $this->language->get('oct_menu_catalog');

			$this->load->model('catalog/information');

			if (isset($oct_feelmart_data['mobile_links']) && !empty($oct_feelmart_data['mobile_links'])) {
                foreach ($oct_feelmart_data['mobile_links'] as $mobile_link) {
					$data['mobile_informations'][] = array(
						'title' => html_entity_decode($mobile_link[(int)$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8'),
						'href'  => $mobile_link[(int)$this->config->get('config_language_id')]['link']
					);
				}
			}

			if (isset($oct_feelmart_data['mobile_menu']['time']) && $oct_feelmart_data['mobile_menu']['time']) {
				if (isset($oct_feelmart_data['contact_open'][(int)$this->config->get('config_language_id')])){
					$oct_contact_opens = explode(PHP_EOL, $oct_feelmart_data['contact_open'][(int)$this->config->get('config_language_id')]);

					foreach ($oct_contact_opens as $oct_contact_open) {
						if (!empty($oct_contact_open)) {
							$data['oct_contact_opens'][] = $oct_contact_open;
						}
					}
				}
			}

			if (isset($oct_feelmart_data['mobile_menu']['phones']) && $oct_feelmart_data['mobile_menu']['phones']) {
				$oct_contact_telephones = explode(PHP_EOL, $oct_feelmart_data['contact_telephone']);

				foreach ($oct_contact_telephones as $oct_contact_telephone) {
					if (!empty($oct_contact_telephone)) {
						$data['oct_contact_telephones'][] = $oct_contact_telephone;
					}
				}
			}

			$data['catalog_menu'] = $this->load->controller('octemplates/module/oct_megamenu', ['mobile' => 1]);

			$data['socials'] = (isset($oct_feelmart_data['socials']) && !empty($oct_feelmart_data['socials'])) ? $oct_feelmart_data['socials'] : false;

			if (isset($oct_feelmart_data['mobile_menu']['address']) && $oct_feelmart_data['mobile_menu']['address']) {
				if (isset($oct_feelmart_data['contact_address'][(int)$this->config->get('config_language_id')]) && !empty($oct_feelmart_data['contact_address'][(int)$this->config->get('config_language_id')])) {
					$data['contact_address'] = html_entity_decode($oct_feelmart_data['contact_address'][(int)$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
				}

				if (isset($oct_feelmart_data['contact_map']) && !empty($oct_feelmart_data['contact_map'])) {
					$data['contact_map'] = html_entity_decode($oct_feelmart_data['contact_map'], ENT_QUOTES, 'UTF-8');
				}
			}

			if (isset($oct_feelmart_data['mobile_menu']['languages']) && $oct_feelmart_data['mobile_menu']['languages']) {
				$data['language'] = true;
			}

			if (isset($oct_feelmart_data['mobile_menu']['currency']) && $oct_feelmart_data['mobile_menu']['currency']) {
				$data['currency'] = true;
			}

			$this->response->setOutput($this->load->view('octemplates/menu/oct_mobile_menu', $data));
		} else {
			$this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}

	public function mobileProductViews() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->language('product/product');

			$settings = [
				'width' => 200,
				'height' => 200,
				'limit' => 20,
				'mobile' => 1
			];


			$data['oct_popup_view_status'] = $this->config->get('oct_popup_view_status');
			
			$data['products'] = $this->load->controller('extension/module/oct_product_views', $settings);

			if ($data['products']) {
				$this->response->setOutput($this->load->view('octemplates/menu/components/oct_mobile_product_views', $data));
			}
		} else {
			$this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}
}
