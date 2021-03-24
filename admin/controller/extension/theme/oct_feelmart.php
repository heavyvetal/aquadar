<?php
/********************************************************/
/*	@copyright	OCTemplates 2015-2020					*/
/*	@support	https://octemplates.net/				*/
/*	@license	LICENSE.txt								*/
/********************************************************/

class ControllerExtensionThemeOCTFeelMart extends Controller {
	private $error = [];
	private $gretret = [];
	private $version = '1.6.6';

	public function index() {
		$this->load->language('octemplates/theme/oct_feelmart');

		$this->load->model('setting/setting');
		$this->load->model('catalog/category');
		$this->load->model('localisation/language');
		$this->load->model('catalog/information');
		$this->load->model('tool/image');
		$this->load->model('setting/store');

		$store_id = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;

		if (isset($this->request->get['store_id']) && $this->request->get['store_id']) {
			$this->load->model('setting/store');

			$store = $this->model_setting_store->getStore($this->request->get['store_id']);

			$data['heading_title'] = $theme_title = $this->language->get('heading_title') . ' ('. $store['name'] .')';

            $this->document->setTitle($this->language->get('heading_title'));
        } else {
	        $data['heading_title'] = $theme_title = $this->language->get('heading_title') . ' ('. $this->config->get('config_name') .')';

            $this->document->setTitle($theme_title);
        }

		$this->document->addScript('view/javascript/octemplates/bootstrap-notify/bootstrap-notify.min.js');
		$this->document->addScript('view/javascript/octemplates/codemirror/lib/codemirror.js');
		$this->document->addScript('view/javascript/octemplates/codemirror/mode/javascript/javascript.js');
		$this->document->addScript('view/javascript/octemplates/codemirror/mode/css/css.js');
		$this->document->addStyle('view/javascript/octemplates/codemirror/lib/codemirror.css');

		//Add Summernote Styles && Scripts
        $this->document->addScript('view/javascript/summernote/summernote.js');
        $this->document->addScript('view/javascript/summernote/summernote-image-attributes.js');
        $this->document->addScript('view/javascript/summernote/opencart.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

		$this->document->addStyle('view/javascript/octemplates/spectrum/spectrum.css');
		$this->document->addScript('view/javascript/octemplates/spectrum/spectrum.js');

		$this->document->addScript('view/javascript/octemplates/oct_main.js');
		$this->document->addStyle('view/stylesheet/oct_feelmart.css');

		$this->{$this->_1774318978(30)}[$this->_1774318978(31)]=[base64_decode('aXN' .'fY' .'X' .'Jy' .'YXk' .'='),base64_decode('c3RycG9z'),base64_decode('' .'c' .'G' .'Fyc2VfdXJs'),base64_decode('cHJ' .'lZ19tYXRj' .'aA=='),base64_decode('c3RyX3' .'J' .'l' .'c' .'Gxh' .'Y2U='),base64_decode('c' .'HJ' .'lZ1' .'9' .'tYXRjaA' .'=='),base64_decode('' .'c' .'3' .'Vic3Ry'),base64_decode('c2hhMQ=='),base64_decode('c3RybGVu'),base64_decode('c2' .'hh' .'MQ=' .'='),base64_decode('c3Vic3Ry'),base64_decode('c2hhMQ=='),base64_decode('c3R' .'y' .'dG9' .'1cHB' .'l' .'c' .'g' .'==')];
		if(($this->{$this->_1774318978(0)}->{$this->_1774318978(1)}[$this->_1774318978(2)]==$this->_1774318978(3))&&$this->mf__f9ab05454998236921a6b0e281fae632()){$this->{$this->_1774318978(4)}->{$this->_1774318978(5)}($this->_1774318978(6),$this->{$this->_1774318978(0)}->{$this->_1774318978(7)},$this->{$this->_1774318978(0)}->{$this->_1774318978(8)}[$this->_1774318978(9)]);$this->{$this->_1774318978(10)}($this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_1774318978(11)], $this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_1774318978(12)],$this->{$this->_1774318978(0)}->{$this->_1774318978(8)}[$this->_1774318978(9)]);$this->{$this->_1774318978(13)}->{$this->_1774318978(14)}[$this->_1774318978(15)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_1774318978(17));$this->{$this->_1774318978(18)}->{$this->_1774318978(19)}($this->{$this->_1774318978(20)}->{$this->_1774318978(21)}($this->_1774318978(22), $this->_1774318978(23).'='.$this->{$this->_1774318978(13)}->{$this->_1774318978(14)}[$this->_1774318978(23)].'&'.$this->_1774318978(9).'='.$this->{$this->_1774318978(0)}->{$this->_1774318978(8)}[$this->_1774318978(9)],true));}

		$oct_error_data = [
			'warning',
			'license',
			'product_limit',
			'product_description_length',
			'image_category',
			'image_sub_category',
			'image_thumb',
			'image_popup',
			'image_product',
			'image_manufacturer',
			'image_additional',
			'image_related',
			'image_compare',
			'image_wishlist',
			'image_cart',
			'image_location',
		];

		foreach ($oct_error_data as $error) {
			if (isset($this->error[$error])) {
				$data['error_'.$error] = $this->error[$error];
			} else {
				$data['error_'.$error] = '';
			}
		}

		if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true)
		];

		$data['action'] = $this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);
		$data['cache_delete'] = $this->url->link('extension/theme/oct_feelmart/cacheDelete', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);
		$data['clear_modification'] = $this->url->link('extension/theme/oct_feelmart/refresh', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true);

		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$setting_info = $this->model_setting_setting->getSetting('theme_oct_feelmart', $this->request->get['store_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];
		$data['store_id'] = $this->request->get['store_id'];

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['theme_oct_feelmart_directory'] = 'oct_feelmart';

		foreach ($this->octSettings() as $config) {
			if (isset($this->request->post[$config])) {
				$data[$config] = $this->request->post[$config];
			} elseif (isset($setting_info[$config])) {
				$data[$config] = $setting_info[$config];
			} else {
				$data[$config] = $this->config->get($config);
			}
		}

		$data['update'] = false;

		if (!isset($data['theme_oct_feelmart_version']) || $data['theme_oct_feelmart_version'] != $this->version) {
			$data['update'] = $this->url->link('extension/theme/oct_feelmart/update', 'user_token=' . $this->session->data['user_token'] . '&type=theme' . '&store_id=' . $this->request->get['store_id'], true);
		}

		if (isset($data['theme_oct_feelmart_lazyload_image']) && !empty($data['theme_oct_feelmart_lazyload_image']) && file_exists(DIR_IMAGE.$data['theme_oct_feelmart_lazyload_image'])) {
			$data['image_lazy'] = $data['theme_oct_feelmart_lazyload_image'];
			$data['thumb_lazy'] = $this->model_tool_image->resize($data['theme_oct_feelmart_lazyload_image'], 30, 30);
		} else {
			$data['image_lazy'] = '';
			$data['thumb_lazy'] = $this->model_tool_image->resize('no_image.png', 30, 30);
		}

		if (isset($data['theme_oct_feelmart_data']['payments']['customers']) && !empty($data['theme_oct_feelmart_data']['payments']['customers'])) {
			foreach ($data['theme_oct_feelmart_data']['payments']['customers'] as $key => $img) {
				if (isset($img['image']) && !empty($img['image']) && file_exists(DIR_IMAGE.$img['image'])) {
					$data['theme_oct_feelmart_data']['payments']['customers'][$key]['thumb_image'] = $this->model_tool_image->resize($img['image'], 52, 32);
				} else {
					$data['theme_oct_feelmart_data']['payments']['customers'][$key]['thumb_image'] = $this->model_tool_image->resize('no_image.png', 52, 32);
				}
			}
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 30, 30);

		$data['links_categories'] = [];

		if (isset($data['theme_oct_feelmart_data']['footer_category_links']) && !empty($data['theme_oct_feelmart_data']['footer_category_links'])) {
			foreach ($data['theme_oct_feelmart_data']['footer_category_links'] as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if ($category_info) {
					$data['links_categories'][] = [
						'category_id' => $category_info['category_id'],
						'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
					];
				}
			}
		}

		$data['header_links'] = [];

		if (isset($data['theme_oct_feelmart_data']['header_information_links']) && !empty($data['theme_oct_feelmart_data']['header_information_links'])) {
			foreach ($data['theme_oct_feelmart_data']['header_information_links'] as $key=>$information_id) {
				$information_info = $this->model_catalog_information->getInformation($information_id);

				if ($information_info) {
					$information_desc = $this->model_catalog_information->getInformationDescriptions($information_id);
					$information_href = $this->model_catalog_information->getInformationSeoUrls($information_id);

					foreach ($data['languages'] as $langs) {
						$data['header_links'][$key][$langs['language_id']]['title'] = $information_desc[$langs['language_id']]['title'];
						$data['header_links'][$key][$langs['language_id']]['link'] = !empty($information_href) ? '/'. $information_href[$store_id][$langs['language_id']] : '/index.php?route=information/information&information_id='.$information_id;
					}
				}
			}
		}

		if (!empty($data['header_links'])) {
			$data['theme_oct_feelmart_data']['header_links'] = $data['header_links'];
		}

		$data['footer_links'] = [];

		if (isset($data['theme_oct_feelmart_data']['footer_information_links']) && !empty($data['theme_oct_feelmart_data']['footer_information_links'])) {
			foreach ($data['theme_oct_feelmart_data']['footer_information_links'] as $key=>$information_id) {
				$information_info = $this->model_catalog_information->getInformation($information_id);

				if ($information_info) {
					$information_desc = $this->model_catalog_information->getInformationDescriptions($information_id);
					$information_href = $this->model_catalog_information->getInformationSeoUrls($information_id);

					foreach ($data['languages'] as $langs) {
						$data['footer_links'][$key][$langs['language_id']]['title'] = $information_desc[$langs['language_id']]['title'];
						$data['footer_links'][$key][$langs['language_id']]['link'] = !empty($information_href) ? '/'. $information_href[$store_id][$langs['language_id']] : '/index.php?route=information/information&information_id='.$information_id;
					}
				}
			}
		}

		if (!empty($data['footer_links'])) {
			$data['theme_oct_feelmart_data']['footer_links'] = $data['footer_links'];
		}

		$data['mobile_links'] = [];

		if (isset($data['theme_oct_feelmart_data']['mobile_information_links']) && !empty($data['theme_oct_feelmart_data']['mobile_information_links'])) {
			foreach ($data['theme_oct_feelmart_data']['mobile_information_links'] as $key=>$information_id) {
				$information_info = $this->model_catalog_information->getInformation($information_id);

				if ($information_info) {
					$information_desc = $this->model_catalog_information->getInformationDescriptions($information_id);
					$information_href = $this->model_catalog_information->getInformationSeoUrls($information_id);

					foreach ($data['languages'] as $langs) {
						$data['mobile_links'][$key][$langs['language_id']]['title'] = $information_desc[$langs['language_id']]['title'];
						$data['mobile_links'][$key][$langs['language_id']]['link'] = !empty($information_href) ? '/'. $information_href[$store_id][$langs['language_id']] : '/index.php?route=information/information&information_id='.$information_id;
					}
				}
			}
		}

		if (!empty($data['mobile_links'])) {
			$data['theme_oct_feelmart_data']['mobile_links'] = $data['mobile_links'];
		}

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default_theme'),
			'href'		=> $this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id=0', true)
		];

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$store_info = $this->model_setting_setting->getSetting('theme_oct_feelmart', $store['store_id']);

			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name'],
				'href'		=> $store_info ? $this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id'], true) : $this->url->link('extension/theme/oct_feelmart/installStore', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id'], true)
			];
		}

		$data['oct_modules'] = $this->getOctExtensions('module');
		$data['oct_banners'] = $this->getOctExtensions('design');
		$data['oct_blogs'] = $this->getOctExtensions('blog');
		$data['oct_stickers'] = $this->getOctExtensions('stickers');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('octemplates/theme/oct_feelmart', $data));
	}

	public function installStore() {
		$this->load->language('octemplates/theme/oct_feelmart');

		$this->load->model('setting/setting');

		$store_id = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;

		$store_info = $this->model_setting_setting->getSetting('theme_oct_feelmart', $store_id);

		if ($store_info) {
			$this->response->redirect($this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true));
		} else {
			$setting_info = $this->model_setting_setting->getSetting('theme_oct_feelmart', 0);

			$data['theme_oct_feelmart_directory'] = 'oct_feelmart';

			foreach ($this->octSettings() as $config) {
				if (isset($setting_info[$config])) {
					$data[$config] = $setting_info[$config];
				} else {
					$data[$config] = $this->config->get($config);
				}
			}

			$this->model_setting_setting->editSetting('theme_oct_feelmart', $this->request->post, $this->request->get['store_id']);

			$this->generateCss($this->request->post['theme_oct_feelmart_data_colors'], $this->request->post['theme_oct_feelmart_data_colors'], $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id='. $store_id, true));
		}
	}

	public function getIcons() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	        $data = [];

	        if (isset($this->request->get['icone_id']) && isset($this->request->get['input_id'])) {
	            $data['icone_id'] = $this->request->get['icone_id'];
	            $data['input_id'] = $this->request->get['input_id'];

	            $data['fa_icons'] = $this->faIcons();

	            $this->response->setOutput($this->load->view('octemplates/oct_icons', $data));
	        }
        }
    }

    private function getOctExtensions($type = 'module') {
	    $this->load->model('setting/setting');

	    $extensions = [];

	    $module_files = glob(DIR_APPLICATION . 'controller/octemplates/'. $type .'/*.php');

		if ($module_files) {
			$data['extensions'] = [];

			foreach ($module_files as $file) {
				$extension = basename($file, '.php');
				$filesize = filesize($file);

				if ($filesize) {
					$this->load->language('octemplates/'. $type .'/' . $extension, 'extension');

					$module_info = $this->model_setting_setting->getSetting($extension);

					$data['extensions'][] = [
						'name'      => $this->language->get('extension')->get('heading_title'),
						'edit'      => $this->url->link('octemplates/'. $type .'/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
					];
				}
			}

			$sort_order = [];

			foreach ($data['extensions'] as $key => $value) {
				$sort_order[$key] = $value['name'];
			}

			array_multisort($sort_order, SORT_ASC, $data['extensions']);

			return $data['extensions'];
		}
    }

    private function generateCss($data, $css_code, $store_id) {
		if (isset($data['main_color']) && !empty($data['main_color'])) {
		    $css = "#back-top, #fm_fixed_contact_button, .fm-fixed-contact-pulsation, .pagination li.active span, .pagination li a:hover, .image-additional-box .owl-carousel .owl-nav button:hover, #fm_livesearch_close, .simplecheckout-cart-buttons .button, .simplecheckout-button-right .button, #simplecheckout_button_login, .ocfilter .panel-heading, .fm-module-column-box .panel-heading, #column-left .panel-heading, #column-right .panel-heading, .fm-btn, .fm-main-footer-contact .fm-main-footer-contact-btn:hover, .ocfilter .noUi-connect, .fm-column-title, .fm-product-btn:hover, .fm-product-quantity button:hover, .fm-column-module-btn:hover, .owl-carousel.image-additional-list .owl-nav button.owl-next:hover, .owl-carousel.image-additional-list .owl-nav button.owl-prev:hover, .fm-account-list .fm-account-item:hover a, .radio-box::after, .product-layout .fm-product-btn {background:". $data['main_color'] .";}".PHP_EOL;
		    $css .= "@media (min-width: 1200px){.product-layout:hover .fm-product-btn{background:". $data['main_color'] .";}}".PHP_EOL;
		    $css .= ".fm-input-with-btn:hover, .fm-product-btn-cart {background:". $data['main_color'] ." !important;}".PHP_EOL;
			$css .= ".btn-header i:first-child, .fm-phone-img i, .fm-module-buttons-list button:hover, .fm-main-footer-contact .btn-link, .fm-main-footer-contact .btn-link:hover, .fm-main-footer-contact .fm-main-footer-contact-link, .fm-main-footer-shedule-list li, .fm-category-appearance-btn.active, .fm-category-appearance-btn:hover, .fm-phone-content .fm-phone-link, .fm-breadcrumb-item a, .fm-advantages-block:hover .fm-advantages-img, .fm-advantages-block:hover .fm-advantages-title, .fm-main-footer-contact .btn-link:focus {color:". $data['main_color'] .";}".PHP_EOL;
			$css .= ".fm-main-footer-contact .fm-main-footer-contact-btn, .fm-main-footer-contact .fm-main-footer-contact-btn:hover, li.fm-product-description-nav-item-active a::after, li.fm-product-description-nav-item:hover a::after, .fm-page-main-title::after, .fm-account-list .fm-account-item:hover {border-color:". $data['main_color'] .";}".PHP_EOL;
		}

		if (isset($data['fon_color']) && !empty($data['fon_color'])) {
			$css .= "body {background-color:". $data['fon_color'] .";}".PHP_EOL;
		}

		if (isset($data['main_link_color']) && !empty($data['main_link_color'])) {
			$css .= ".fm_mobile_menu_info-list-item a, .fm_mobile_menu_info .dropdown-menu button.active-item, .fm-acc-info-link, .fm_mobile_menu_cart-text a, .form-checkbox-group label a, .subcat-item:hover .subcat-item-title, .fm-table-compare .fm-table-product-name a:hover, .fm-html-box-text a, .fm-category-wall-item ul a:hover, .fm-category-wall-item-title:hover span, .promotional-product-item-title a:hover, #oct-menu-ul .group-title a, .fm-reviews-item:hover .fm-reviews-item-title, .fm-module-btn:hover, .fm-news-item:hover .fm-news-item-title, .fm-footer-subscribe-subscribe .form-checkbox-group label a, .fm-livesearch-list-item-name a, .fm-fixed-contact-item:hover, .category-landing-links-item-link span, .fm-product-left-top-info-block-brand a, .fm-product-left-top-info-block-reviews a, .fm-price-block-link, .fm-price-block-link:hover, .fm-product-buttons-list a:hover span, .fm-product-buttons-list a:hover i, .fm-inform-content-text a, .fm-inform-content-text-title a, .oct-mm-item-title:hover, .oct-mm-item-list li a:hover, .oct-mm-item-list li.oct-mm-item-see-all, .fm-content-reviews .fm-review-rating-shop, .fm-blog-categories-module-list a:hover, .fm-blog-post-content .fm-news-item-text a, .fm-blog-post-content .fm-news-item-text a:hover, .login-popup-links a, .nav-dropdown-menu button:hover, .fm-phone-box .phoneclick:hover, .fm-content-contact-offer .fm-content-contact-text a, .fm-content-manufacturer .fm-content-title a, .fm-manufacturer-item .fm-manufacturer-card:hover span, .header-dropdown li a:hover, .nav-dropdown-menu .my-dropdown-item a:hover, .fm-acc-info-text a, .fm-acc-info-text a:hover, .table td a, .fm-acc-info-link:hover, .fm-acc-info-block-title:hover, .fm-category-description p a, .fm-product-description-cont a, .fm-alert-text a, .fm-subcat-show-link, .fm-subcat-show-link:hover {color:". $data['main_link_color'] .";}".PHP_EOL;
			$css .= "@media (min-width: 1200px){.fm-module-item:hover .fm-module-title > a, .fm-module-item:hover .fm-module-title > a:visited{color:". $data['main_link_color'] .";}}".PHP_EOL;
		}

		if (isset($data['top_fon_color']) && !empty($data['top_fon_color'])) {
			$css .= "#top {background:". $data['top_fon_color'] .";}".PHP_EOL;
		}

		if (isset($data['top_link_color']) && !empty($data['top_link_color'])) {
			$css .= ".btn-link, .info-link {color:". $data['top_link_color'] .";}".PHP_EOL;
		}

		if (isset($data['top_link_color_hover']) && !empty($data['top_link_color_hover'])) {
			$css .= "#top .btn-link:hover, #top .btn-link:focus, nav.top .info-list a:hover {color:". $data['top_link_color_hover'] ." ;}".PHP_EOL;
		}

		if (isset($data['search_bg']) && !empty($data['search_bg'])) {
			$css .= ".fm-search input {background:". $data['search_bg'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_color']) && !empty($data['menu_fon_color'])) {
			$css .= ".menu-row {background-color:". $data['menu_fon_color'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_cat_color']) && !empty($data['menu_fon_cat_color'])) {
			$css .= ".fm-menu {background:". $data['menu_fon_cat_color'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_cat_hover_color']) && !empty($data['menu_fon_cat_hover_color'])) {
			$css .= "#oct-menu-box:hover .fm-menu {background-color:". $data['menu_fon_cat_hover_color'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_cat_text_color']) && !empty($data['menu_fon_cat_text_color'])) {
			$css .= ".menu-row {color:". $data['menu_fon_cat_text_color'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_cat_elements_color']) && !empty($data['menu_fon_cat_elements_color'])) {
			$css .= ".oct-menu-li {background:". $data['menu_fon_cat_elements_color'] .";}".PHP_EOL;
		}

		if (isset($data['menu_fon_cat_link_color']) && !empty($data['menu_fon_cat_link_color'])) {
			$css .= ".oct-menu-li > a, .oct-menu-li > div > a {color:". $data['menu_fon_cat_link_color'] .";}".PHP_EOL;
		}

		if (isset($data['footer_fon_color']) && !empty($data['footer_fon_color'])) {
			$css .= ".fm-main-footer, .fm-footer-subscribe {background-color:". $data['footer_fon_color'] .";}".PHP_EOL;
		}

		if (isset($data['footer_text_color']) && !empty($data['footer_text_color'])) {
			$css .= ".fm-footer-subscribe, footer, .fm-main-footer-contact-address, .fm-main-footer-title {color:". $data['footer_text_color'] .";}".PHP_EOL;
		}

		if (isset($data['footer_link_color']) && !empty($data['footer_link_color'])) {
			$css .= ".fm-main-footer-links-list a, .fm-main-footer-credits {color:". $data['footer_link_color'] .";}".PHP_EOL;
		}

		if (isset($data['footer_link_hover_color']) && !empty($data['footer_link_hover_color'])) {
			$css .= ".fm-main-footer-links-list a:hover {color:". $data['footer_link_hover_color'] .";}".PHP_EOL;
		}

		if (isset($data['category_module_fon_color']) && !empty($data['category_module_fon_color'])) {
			$css .= ".fm-categories-box {background:". $data['category_module_fon_color'] .";}".PHP_EOL;
		}

		if (isset($data['category_module_link_color']) && !empty($data['category_module_link_color'])) {
			$css .= ".fm-categories-item {color:". $data['category_module_link_color'] .";}".PHP_EOL;
		}

		if (isset($data['category_module_link_hover_color']) && !empty($data['category_module_link_hover_color'])) {
			$css .= ".fm-categories-item.active > span a, .fm-categories-item a:hover {color:". $data['category_module_link_hover_color'] .";}".PHP_EOL;
		}

		if (isset($data['modal_fon_title_color']) && !empty($data['modal_fon_title_color'])) {
			$css .= ".modal-header {background:". $data['modal_fon_title_color'] .";}".PHP_EOL;
		}

		if (isset($data['modal_text_title_color']) && !empty($data['modal_text_title_color'])) {
			$css .= ".modal-title {color:". $data['modal_text_title_color'] .";}".PHP_EOL;
		}

		if (isset($data['modal_fon_icon_color']) && !empty($data['modal_fon_icon_color'])) {
			$css .= ".fm-modal-close-icon {border-color:". $data['modal_fon_icon_color'] .";}".PHP_EOL;
		}

		if (isset($data['mobile_fon_top_color']) && !empty($data['mobile_fon_top_color'])) {
			$css .= "#fm_mobile_nav {background:". $data['mobile_fon_top_color'] .";}".PHP_EOL;
		}

		if (isset($data['mobile_fon_icon_c_color']) && !empty($data['mobile_fon_icon_c_color'])) {
			$css .= "#fm_mobile_menu_button {background-color:". $data['mobile_fon_icon_c_color'] ." !important;}".PHP_EOL;
		}

		if (isset($data['logo_width']) && ($data['logo_width'] || $data['logo_width'] == 'on')) {
			$css .= ".fm-logo img {max-width: 100%!important;}".PHP_EOL;
		}

		if (!isset($data['compare'])) {
			$css .= ".fm-menu-buttons-compare, .fm-product-buttons-link-compare, .fm-module-buttons-compare, #fm_mobile_compare_button {display: none!important;}".PHP_EOL;
		}

		if (!isset($data['wishlist'])) {
			$css .= ".fm-menu-buttons-wishlist, .fm-product-buttons-link-wishlist, .fm-module-buttons-wishlist, #fm_mobile_wishlist_button {display: none!important;}".PHP_EOL;
		}

		if (!empty($css_code)) {
			$css .= html_entity_decode($css_code, ENT_QUOTES, 'UTF-8');
		}

		file_put_contents(DIR_CATALOG . 'view/theme/oct_feelmart/stylesheet/dynamic_stylesheet_'. $store_id .'.css', $css);
    }

    public function cacheDelete() {
	    if ($this->mf__6262eb5c2a83c9f29edc0359ada36fe4()) {
		    $this->load->language('octemplates/theme/oct_feelmart');

		    $maintenance = $this->config->get('config_maintenance');

		    $this->load->model('setting/setting');

		    $this->cache->delete('octemplates');

			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', true);

	        if (isset($this->request->get['store_id'])) {
	            $store_id = $this->request->get['store_id'];
	        } else {
	            $store_id = 0;
	        }

		    $setting_info = $this->model_setting_setting->getSetting('theme_oct_feelmart', $store_id);

		    $oct_colors = isset($setting_info['theme_oct_feelmart_data_colors']) && !empty($setting_info['theme_oct_feelmart_data_colors']) ? $setting_info['theme_oct_feelmart_data_colors'] : [];
		    $oct_css_code = isset($setting_info['theme_oct_feelmart_css_code']) && !empty($setting_info['theme_oct_feelmart_css_code']) ? $setting_info['theme_oct_feelmart_css_code'] : '';

		    $this->generateCss($oct_colors, $oct_css_code, $store_id);

			if (file_exists(DIR_CATALOG. "view/javascript/ocfilter/ocfilter.js")) {
			    $OCFilter_js = file_get_contents(DIR_CATALOG. "view/javascript/ocfilter/ocfilter.js");

			    $check_exist_old_string = preg_match('/\.popover\(\'destroy\'\);/', $OCFilter_js);

			    if($check_exist_old_string) {
			        $OCFilter_js_to = str_replace(".popover('destroy');", ".popover('dispose');", $OCFilter_js);

			        file_put_contents(DIR_CATALOG. "view/javascript/ocfilter/ocfilter.js", "");
			        file_put_contents(DIR_CATALOG. "view/javascript/ocfilter/ocfilter.js", $OCFilter_js_to);
			    }
			}

		    if (is_dir($this->request->server['DOCUMENT_ROOT'] . '/min/cache/')) {
		    	$this->delTree($this->request->server['DOCUMENT_ROOT'] . '/min/cache/');
		    }

			$this->delTree(DIR_CACHE);

			if (!file_exists(DIR_CACHE)) {
				mkdir(DIR_CACHE);

				$addindexf = fopen(DIR_CACHE .'index.html', 'w');
				fclose($addindexf);
			}

		    $this->session->data['success'] = $this->language->get('text_success_cache');

		    // Maintance mode back to original settings
			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);
		}

		$this->response->redirect($this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&store_id='.$this->request->get['store_id'], true));
    }

	private function delTree($dir) {
		$files = array_diff(scandir($dir), ['.','..']);

		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
		}

		return rmdir($dir);
	}

    private function faIcons() {
	    return [
	        'fab fa-500px' => '500px',
	        'fab fa-accessible-icon' => 'accessible-icon',
	        'fab fa-accusoft' => 'accusoft',
	        'fas fa-address-book' => 'address-book',
	        'far fa-address-book' => 'address-book',
	        'fas fa-address-card' => 'address-card',
	        'far fa-address-card' => 'address-card',
	        'fas fa-adjust' => 'adjust',
	        'fab fa-adn' => 'adn',
	        'fab fa-adversal' => 'adversal',
	        'fab fa-affiliatetheme' => 'affiliatetheme',
	        'fab fa-algolia' => 'algolia',
	        'fas fa-align-center' => 'align-center',
	        'fas fa-align-justify' => 'align-justify',
	        'fas fa-align-left' => 'align-left',
	        'fas fa-align-right' => 'align-right',
	        'fas fa-allergies' => 'allergies',
	        'fab fa-amazon' => 'amazon',
	        'fab fa-amazon-pay' => 'amazon-pay',
	        'fas fa-ambulance' => 'ambulance',
	        'fas fa-american-sign-language-interpreting' => 'american-sign-language-interpreting',
	        'fab fa-amilia' => 'amilia',
	        'fas fa-anchor' => 'anchor',
	        'fab fa-android' => 'android',
	        'fab fa-angellist' => 'angellist',
	        'fas fa-angle-double-down' => 'angle-double-down',
	        'fas fa-angle-double-left' => 'angle-double-left',
	        'fas fa-angle-double-right' => 'angle-double-right',
	        'fas fa-angle-double-up' => 'angle-double-up',
	        'fas fa-angle-down' => 'angle-down',
	        'fas fa-angle-left' => 'angle-left',
	        'fas fa-angle-right' => 'angle-right',
	        'fas fa-angle-up' => 'angle-up',
	        'fab fa-angrycreative' => 'angrycreative',
	        'fab fa-angular' => 'angular',
	        'fab fa-app-store' => 'app-store',
	        'fab fa-app-store-ios' => 'app-store-ios',
	        'fab fa-apper' => 'apper',
	        'fab fa-apple' => 'apple',
	        'fab fa-apple-pay' => 'apple-pay',
	        'fas fa-archive' => 'archive',
	        'fas fa-arrow-alt-circle-down' => 'arrow-alt-circle-down',
	        'far fa-arrow-alt-circle-down' => 'arrow-alt-circle-down',
	        'fas fa-arrow-alt-circle-left' => 'arrow-alt-circle-left',
	        'far fa-arrow-alt-circle-left' => 'arrow-alt-circle-left',
	        'fas fa-arrow-alt-circle-right' => 'arrow-alt-circle-right',
	        'far fa-arrow-alt-circle-right' => 'arrow-alt-circle-right',
	        'fas fa-arrow-alt-circle-up' => 'arrow-alt-circle-up',
	        'far fa-arrow-alt-circle-up' => 'arrow-alt-circle-up',
	        'fas fa-arrow-circle-down' => 'arrow-circle-down',
	        'fas fa-arrow-circle-left' => 'arrow-circle-left',
	        'fas fa-arrow-circle-right' => 'arrow-circle-right',
	        'fas fa-arrow-circle-up' => 'arrow-circle-up',
	        'fas fa-arrow-down' => 'arrow-down',
	        'fas fa-arrow-left' => 'arrow-left',
	        'fas fa-arrow-right' => 'arrow-right',
	        'fas fa-arrow-up' => 'arrow-up',
	        'fas fa-arrows-alt' => 'arrows-alt',
	        'fas fa-arrows-alt-h' => 'arrows-alt-h',
	        'fas fa-arrows-alt-v' => 'arrows-alt-v',
	        'fas fa-assistive-listening-systems' => 'assistive-listening-systems',
	        'fas fa-asterisk' => 'asterisk',
	        'fab fa-asymmetrik' => 'asymmetrik',
	        'fas fa-at' => 'at',
	        'fab fa-audible' => 'audible',
	        'fas fa-audio-description' => 'audio-description',
	        'fab fa-autoprefixer' => 'autoprefixer',
	        'fab fa-avianex' => 'avianex',
	        'fab fa-aviato' => 'aviato',
	        'fab fa-aws' => 'aws',
	        'fas fa-backward' => 'backward',
	        'fas fa-balance-scale' => 'balance-scale',
	        'fas fa-ban' => 'ban',
	        'fas fa-band-aid' => 'band-aid',
	        'fab fa-bandcamp' => 'bandcamp',
	        'fas fa-barcode' => 'barcode',
	        'fas fa-bars' => 'bars',
	        'fas fa-baseball-ball' => 'baseball-ball',
	        'fas fa-basketball-ball' => 'basketball-ball',
	        'fas fa-bath' => 'bath',
	        'fas fa-battery-empty' => 'battery-empty',
	        'fas fa-battery-full' => 'battery-full',
	        'fas fa-battery-half' => 'battery-half',
	        'fas fa-battery-quarter' => 'battery-quarter',
	        'fas fa-battery-three-quarters' => 'battery-three-quarters',
	        'fas fa-bed' => 'bed',
	        'fas fa-beer' => 'beer',
	        'fab fa-behance' => 'behance',
	        'fab fa-behance-square' => 'behance-square',
	        'fas fa-bell' => 'bell',
	        'far fa-bell' => 'bell',
	        'fas fa-bell-slash' => 'bell-slash',
	        'far fa-bell-slash' => 'bell-slash',
	        'fas fa-bicycle' => 'bicycle',
	        'fab fa-bimobject' => 'bimobject',
	        'fas fa-binoculars' => 'binoculars',
	        'fas fa-birthday-cake' => 'birthday-cake',
	        'fab fa-bitbucket' => 'bitbucket',
	        'fab fa-bitcoin' => 'bitcoin',
	        'fab fa-bity' => 'bity',
	        'fab fa-black-tie' => 'black-tie',
	        'fab fa-blackberry' => 'blackberry',
	        'fas fa-blind' => 'blind',
	        'fab fa-blogger' => 'blogger',
	        'fab fa-blogger-b' => 'blogger-b',
	        'fab fa-bluetooth' => 'bluetooth',
	        'fab fa-bluetooth-b' => 'bluetooth-b',
	        'fas fa-bold' => 'bold',
	        'fas fa-bolt' => 'bolt',
	        'fas fa-bomb' => 'bomb',
	        'fas fa-book' => 'book',
	        'fas fa-bookmark' => 'bookmark',
	        'far fa-bookmark' => 'bookmark',
	        'fas fa-bowling-ball' => 'bowling-ball',
	        'fas fa-box' => 'box',
	        'fas fa-box-open' => 'box-open',
	        'fas fa-boxes' => 'boxes',
	        'fas fa-braille' => 'braille',
	        'fas fa-briefcase' => 'briefcase',
	        'fas fa-briefcase-medical' => 'briefcase-medical',
	        'fab fa-btc' => 'btc',
	        'fas fa-bug' => 'bug',
	        'fas fa-building' => 'building',
	        'far fa-building' => 'building',
	        'fas fa-bullhorn' => 'bullhorn',
	        'fas fa-bullseye' => 'bullseye',
	        'fas fa-burn' => 'burn',
	        'fab fa-buromobelexperte' => 'buromobelexperte',
	        'fas fa-bus' => 'bus',
	        'fab fa-buysellads' => 'buysellads',
	        'fas fa-calculator' => 'calculator',
	        'fas fa-calendar' => 'calendar',
	        'far fa-calendar' => 'calendar',
	        'fas fa-calendar-alt' => 'calendar-alt',
	        'far fa-calendar-alt' => 'calendar-alt',
	        'fas fa-calendar-check' => 'calendar-check',
	        'far fa-calendar-check' => 'calendar-check',
	        'fas fa-calendar-minus' => 'calendar-minus',
	        'far fa-calendar-minus' => 'calendar-minus',
	        'fas fa-calendar-plus' => 'calendar-plus',
	        'far fa-calendar-plus' => 'calendar-plus',
	        'fas fa-calendar-times' => 'calendar-times',
	        'far fa-calendar-times' => 'calendar-times',
	        'fas fa-camera' => 'camera',
	        'fas fa-camera-retro' => 'camera-retro',
	        'fas fa-capsules' => 'capsules',
	        'fas fa-car' => 'car',
	        'fas fa-caret-down' => 'caret-down',
	        'fas fa-caret-left' => 'caret-left',
	        'fas fa-caret-right' => 'caret-right',
	        'fas fa-caret-square-down' => 'caret-square-down',
	        'far fa-caret-square-down' => 'caret-square-down',
	        'fas fa-caret-square-left' => 'caret-square-left',
	        'far fa-caret-square-left' => 'caret-square-left',
	        'fas fa-caret-square-right' => 'caret-square-right',
	        'far fa-caret-square-right' => 'caret-square-right',
	        'fas fa-caret-square-up' => 'caret-square-up',
	        'far fa-caret-square-up' => 'caret-square-up',
	        'fas fa-caret-up' => 'caret-up',
	        'fas fa-cart-arrow-down' => 'cart-arrow-down',
	        'fas fa-cart-plus' => 'cart-plus',
	        'fab fa-cc-amazon-pay' => 'cc-amazon-pay',
	        'fab fa-cc-amex' => 'cc-amex',
	        'fab fa-cc-apple-pay' => 'cc-apple-pay',
	        'fab fa-cc-diners-club' => 'cc-diners-club',
	        'fab fa-cc-discover' => 'cc-discover',
	        'fab fa-cc-jcb' => 'cc-jcb',
	        'fab fa-cc-mastercard' => 'cc-mastercard',
	        'fab fa-cc-paypal' => 'cc-paypal',
	        'fab fa-cc-stripe' => 'cc-stripe',
	        'fab fa-cc-visa' => 'cc-visa',
	        'fab fa-centercode' => 'centercode',
	        'fas fa-certificate' => 'certificate',
	        'fas fa-chart-area' => 'chart-area',
	        'fas fa-chart-bar' => 'chart-bar',
	        'far fa-chart-bar' => 'chart-bar',
	        'fas fa-chart-line' => 'chart-line',
	        'fas fa-chart-pie' => 'chart-pie',
	        'fas fa-check' => 'check',
	        'fas fa-check-circle' => 'check-circle',
	        'far fa-check-circle' => 'check-circle',
	        'fas fa-check-square' => 'check-square',
	        'far fa-check-square' => 'check-square',
	        'fas fa-chess' => 'chess',
	        'fas fa-chess-bishop' => 'chess-bishop',
	        'fas fa-chess-board' => 'chess-board',
	        'fas fa-chess-king' => 'chess-king',
	        'fas fa-chess-knight' => 'chess-knight',
	        'fas fa-chess-pawn' => 'chess-pawn',
	        'fas fa-chess-queen' => 'chess-queen',
	        'fas fa-chess-rook' => 'chess-rook',
	        'fas fa-chevron-circle-down' => 'chevron-circle-down',
	        'fas fa-chevron-circle-left' => 'chevron-circle-left',
	        'fas fa-chevron-circle-right' => 'chevron-circle-right',
	        'fas fa-chevron-circle-up' => 'chevron-circle-up',
	        'fas fa-chevron-down' => 'chevron-down',
	        'fas fa-chevron-left' => 'chevron-left',
	        'fas fa-chevron-right' => 'chevron-right',
	        'fas fa-chevron-up' => 'chevron-up',
	        'fas fa-child' => 'child',
	        'fab fa-chrome' => 'chrome',
	        'fas fa-circle' => 'circle',
	        'far fa-circle' => 'circle',
	        'fas fa-circle-notch' => 'circle-notch',
	        'fas fa-clipboard' => 'clipboard',
	        'far fa-clipboard' => 'clipboard',
	        'fas fa-clipboard-check' => 'clipboard-check',
	        'fas fa-clipboard-list' => 'clipboard-list',
	        'fas fa-clock' => 'clock',
	        'far fa-clock' => 'clock',
	        'fas fa-clone' => 'clone',
	        'far fa-clone' => 'clone',
	        'fas fa-closed-captioning' => 'closed-captioning',
	        'far fa-closed-captioning' => 'closed-captioning',
	        'fas fa-cloud' => 'cloud',
	        'fas fa-cloud-download-alt' => 'cloud-download-alt',
	        'fas fa-cloud-upload-alt' => 'cloud-upload-alt',
	        'fab fa-cloudscale' => 'cloudscale',
	        'fab fa-cloudsmith' => 'cloudsmith',
	        'fab fa-cloudversify' => 'cloudversify',
	        'fas fa-code' => 'code',
	        'fas fa-code-branch' => 'code-branch',
	        'fab fa-codepen' => 'codepen',
	        'fab fa-codiepie' => 'codiepie',
	        'fas fa-coffee' => 'coffee',
	        'fas fa-cog' => 'cog',
	        'fas fa-cogs' => 'cogs',
	        'fas fa-columns' => 'columns',
	        'fas fa-comment' => 'comment',
	        'far fa-comment' => 'comment',
	        'fas fa-comment-alt' => 'comment-alt',
	        'far fa-comment-alt' => 'comment-alt',
	        'fas fa-comment-dots' => 'comment-dots',
	        'fas fa-comment-slash' => 'comment-slash',
	        'fas fa-comments' => 'comments',
	        'far fa-comments' => 'comments',
	        'fas fa-compass' => 'compass',
	        'far fa-compass' => 'compass',
	        'fas fa-compress' => 'compress',
	        'fab fa-connectdevelop' => 'connectdevelop',
	        'fab fa-contao' => 'contao',
	        'fas fa-copy' => 'copy',
	        'far fa-copy' => 'copy',
	        'fas fa-copyright' => 'copyright',
	        'far fa-copyright' => 'copyright',
	        'fas fa-couch' => 'couch',
	        'fab fa-cpanel' => 'cpanel',
	        'fab fa-creative-commons' => 'creative-commons',
	        'fas fa-credit-card' => 'credit-card',
	        'far fa-credit-card' => 'credit-card',
	        'fas fa-crop' => 'crop',
	        'fas fa-crosshairs' => 'crosshairs',
	        'fab fa-css3' => 'css3',
	        'fab fa-css3-alt' => 'css3-alt',
	        'fas fa-cube' => 'cube',
	        'fas fa-cubes' => 'cubes',
	        'fas fa-cut' => 'cut',
	        'fab fa-cuttlefish' => 'cuttlefish',
	        'fab fa-d-and-d' => 'd-and-d',
	        'fab fa-dashcube' => 'dashcube',
	        'fas fa-database' => 'database',
	        'fas fa-deaf' => 'deaf',
	        'fab fa-delicious' => 'delicious',
	        'fab fa-deploydog' => 'deploydog',
	        'fab fa-deskpro' => 'deskpro',
	        'fas fa-desktop' => 'desktop',
	        'fab fa-deviantart' => 'deviantart',
	        'fas fa-diagnoses' => 'diagnoses',
	        'fab fa-digg' => 'digg',
	        'fab fa-digital-ocean' => 'digital-ocean',
	        'fab fa-discord' => 'discord',
	        'fab fa-discourse' => 'discourse',
	        'fas fa-dna' => 'dna',
	        'fab fa-dochub' => 'dochub',
	        'fab fa-docker' => 'docker',
	        'fas fa-dollar-sign' => 'dollar-sign',
	        'fas fa-dolly' => 'dolly',
	        'fas fa-dolly-flatbed' => 'dolly-flatbed',
	        'fas fa-donate' => 'donate',
	        'fas fa-dot-circle' => 'dot-circle',
	        'far fa-dot-circle' => 'dot-circle',
	        'fas fa-dove' => 'dove',
	        'fas fa-download' => 'download',
	        'fab fa-draft2digital' => 'draft2digital',
	        'fab fa-dribbble' => 'dribbble',
	        'fab fa-dribbble-square' => 'dribbble-square',
	        'fab fa-dropbox' => 'dropbox',
	        'fab fa-drupal' => 'drupal',
	        'fab fa-dyalog' => 'dyalog',
	        'fab fa-earlybirds' => 'earlybirds',
	        'fab fa-edge' => 'edge',
	        'fas fa-edit' => 'edit',
	        'far fa-edit' => 'edit',
	        'fas fa-eject' => 'eject',
	        'fab fa-elementor' => 'elementor',
	        'fas fa-ellipsis-h' => 'ellipsis-h',
	        'fas fa-ellipsis-v' => 'ellipsis-v',
	        'fab fa-ember' => 'ember',
	        'fab fa-empire' => 'empire',
	        'fas fa-envelope' => 'envelope',
	        'far fa-envelope' => 'envelope',
	        'fas fa-envelope-open' => 'envelope-open',
	        'far fa-envelope-open' => 'envelope-open',
	        'fas fa-envelope-square' => 'envelope-square',
	        'fab fa-envira' => 'envira',
	        'fas fa-eraser' => 'eraser',
	        'fab fa-erlang' => 'erlang',
	        'fab fa-ethereum' => 'ethereum',
	        'fab fa-etsy' => 'etsy',
	        'fas fa-euro-sign' => 'euro-sign',
	        'fas fa-exchange-alt' => 'exchange-alt',
	        'fas fa-exclamation' => 'exclamation',
	        'fas fa-exclamation-circle' => 'exclamation-circle',
	        'fas fa-exclamation-triangle' => 'exclamation-triangle',
	        'fas fa-expand' => 'expand',
	        'fas fa-expand-arrows-alt' => 'expand-arrows-alt',
	        'fab fa-expeditedssl' => 'expeditedssl',
	        'fas fa-external-link-alt' => 'external-link-alt',
	        'fas fa-external-link-square-alt' => 'external-link-square-alt',
	        'fas fa-eye' => 'eye',
	        'fas fa-eye-dropper' => 'eye-dropper',
	        'fas fa-eye-slash' => 'eye-slash',
	        'far fa-eye-slash' => 'eye-slash',
	        'fab fa-facebook' => 'facebook',
	        'fab fa-facebook-f' => 'facebook-f',
	        'fab fa-facebook-messenger' => 'facebook-messenger',
	        'fab fa-facebook-square' => 'facebook-square',
	        'fas fa-fast-backward' => 'fast-backward',
	        'fas fa-fast-forward' => 'fast-forward',
	        'fas fa-fax' => 'fax',
	        'fas fa-female' => 'female',
	        'fas fa-fighter-jet' => 'fighter-jet',
	        'fas fa-file' => 'file',
	        'far fa-file' => 'file',
	        'fas fa-file-alt' => 'file-alt',
	        'far fa-file-alt' => 'file-alt',
	        'fas fa-file-archive' => 'file-archive',
	        'far fa-file-archive' => 'file-archive',
	        'fas fa-file-audio' => 'file-audio',
	        'far fa-file-audio' => 'file-audio',
	        'fas fa-file-code' => 'file-code',
	        'far fa-file-code' => 'file-code',
	        'fas fa-file-excel' => 'file-excel',
	        'far fa-file-excel' => 'file-excel',
	        'fas fa-file-image' => 'file-image',
	        'far fa-file-image' => 'file-image',
	        'fas fa-file-medical' => 'file-medical',
	        'fas fa-file-medical-alt' => 'file-medical-alt',
	        'fas fa-file-pdf' => 'file-pdf',
	        'far fa-file-pdf' => 'file-pdf',
	        'fas fa-file-powerpoint' => 'file-powerpoint',
	        'far fa-file-powerpoint' => 'file-powerpoint',
	        'fas fa-file-video' => 'file-video',
	        'far fa-file-video' => 'file-video',
	        'fas fa-file-word' => 'file-word',
	        'far fa-file-word' => 'file-word',
	        'fas fa-film' => 'film',
	        'fas fa-filter' => 'filter',
	        'fas fa-fire' => 'fire',
	        'fas fa-fire-extinguisher' => 'fire-extinguisher',
	        'fab fa-firefox' => 'firefox',
	        'fas fa-first-aid' => 'first-aid',
	        'fab fa-first-order' => 'first-order',
	        'fab fa-firstdraft' => 'firstdraft',
	        'fas fa-flag' => 'flag',
	        'far fa-flag' => 'flag',
	        'fas fa-flag-checkered' => 'flag-checkered',
	        'fas fa-flask' => 'flask',
	        'fab fa-flickr' => 'flickr',
	        'fab fa-flipboard' => 'flipboard',
	        'fab fa-fly' => 'fly',
	        'fas fa-folder' => 'folder',
	        'far fa-folder' => 'folder',
	        'fas fa-folder-open' => 'folder-open',
	        'far fa-folder-open' => 'folder-open',
	        'fas fa-font' => 'font',
	        'fab fa-font-awesome' => 'font-awesome',
	        'fab fa-font-awesome-alt' => 'font-awesome-alt',
	        'fab fa-font-awesome-flag' => 'font-awesome-flag',
	        'fab fa-fonticons' => 'fonticons',
	        'fab fa-fonticons-fi' => 'fonticons-fi',
	        'fas fa-football-ball' => 'football-ball',
	        'fab fa-fort-awesome' => 'fort-awesome',
	        'fab fa-fort-awesome-alt' => 'fort-awesome-alt',
	        'fab fa-forumbee' => 'forumbee',
	        'fas fa-forward' => 'forward',
	        'fab fa-foursquare' => 'foursquare',
	        'fab fa-free-code-camp' => 'free-code-camp',
	        'fab fa-freebsd' => 'freebsd',
	        'fas fa-frown' => 'frown',
	        'far fa-frown' => 'frown',
	        'fas fa-futbol' => 'futbol',
	        'far fa-futbol' => 'futbol',
	        'fas fa-gamepad' => 'gamepad',
	        'fas fa-gavel' => 'gavel',
	        'fas fa-gem' => 'gem',
	        'far fa-gem' => 'gem',
	        'fas fa-genderless' => 'genderless',
	        'fab fa-get-pocket' => 'get-pocket',
	        'fab fa-gg' => 'gg',
	        'fab fa-gg-circle' => 'gg-circle',
	        'fas fa-gift' => 'gift',
	        'fab fa-git' => 'git',
	        'fab fa-git-square' => 'git-square',
	        'fab fa-github' => 'github',
	        'fab fa-github-alt' => 'github-alt',
	        'fab fa-github-square' => 'github-square',
	        'fab fa-gitkraken' => 'gitkraken',
	        'fab fa-gitlab' => 'gitlab',
	        'fab fa-gitter' => 'gitter',
	        'fas fa-glass-martini' => 'glass-martini',
	        'fab fa-glide' => 'glide',
	        'fab fa-glide-g' => 'glide-g',
	        'fas fa-globe' => 'globe',
	        'fab fa-gofore' => 'gofore',
	        'fas fa-golf-ball' => 'golf-ball',
	        'fab fa-goodreads' => 'goodreads',
	        'fab fa-goodreads-g' => 'goodreads-g',
	        'fab fa-google' => 'google',
	        'fab fa-google-drive' => 'google-drive',
	        'fab fa-google-play' => 'google-play',
	        'fab fa-google-plus' => 'google-plus',
	        'fab fa-google-plus-g' => 'google-plus-g',
	        'fab fa-google-plus-square' => 'google-plus-square',
	        'fab fa-google-wallet' => 'google-wallet',
	        'fas fa-graduation-cap' => 'graduation-cap',
	        'fab fa-gratipay' => 'gratipay',
	        'fab fa-grav' => 'grav',
	        'fab fa-gripfire' => 'gripfire',
	        'fab fa-grunt' => 'grunt',
	        'fab fa-gulp' => 'gulp',
	        'fas fa-h-square' => 'h-square',
	        'fab fa-hacker-news' => 'hacker-news',
	        'fab fa-hacker-news-square' => 'hacker-news-square',
	        'fas fa-hand-holding' => 'hand-holding',
	        'fas fa-hand-holding-heart' => 'hand-holding-heart',
	        'fas fa-hand-holding-usd' => 'hand-holding-usd',
	        'fas fa-hand-lizard' => 'hand-lizard',
	        'far fa-hand-lizard' => 'hand-lizard',
	        'fas fa-hand-paper' => 'hand-paper',
	        'far fa-hand-paper' => 'hand-paper',
	        'fas fa-hand-peace' => 'hand-peace',
	        'far fa-hand-peace' => 'hand-peace',
	        'fas fa-hand-point-down' => 'hand-point-down',
	        'far fa-hand-point-down' => 'hand-point-down',
	        'fas fa-hand-point-left' => 'hand-point-left',
	        'far fa-hand-point-left' => 'hand-point-left',
	        'fas fa-hand-point-right' => 'hand-point-right',
	        'far fa-hand-point-right' => 'hand-point-right',
	        'fas fa-hand-point-up' => 'hand-point-up',
	        'far fa-hand-point-up' => 'hand-point-up',
	        'fas fa-hand-pointer' => 'hand-pointer',
	        'far fa-hand-pointer' => 'hand-pointer',
	        'fas fa-hand-rock' => 'hand-rock',
	        'far fa-hand-rock' => 'hand-rock',
	        'fas fa-hand-scissors' => 'hand-scissors',
	        'far fa-hand-scissors' => 'hand-scissors',
	        'fas fa-hand-spock' => 'hand-spock',
	        'far fa-hand-spock' => 'hand-spock',
	        'fas fa-hands' => 'hands',
	        'fas fa-hands-helping' => 'hands-helping',
	        'fas fa-handshake' => 'handshake',
	        'far fa-handshake' => 'handshake',
	        'fas fa-hashtag' => 'hashtag',
	        'fas fa-hdd' => 'hdd',
	        'far fa-hdd' => 'hdd',
	        'fas fa-heading' => 'heading',
	        'fas fa-headphones' => 'headphones',
	        'fas fa-heart' => 'heart',
	        'far fa-heart' => 'heart',
	        'fas fa-heartbeat' => 'heartbeat',
	        'fab fa-hips' => 'hips',
	        'fab fa-hire-a-helper' => 'hire-a-helper',
	        'fas fa-history' => 'history',
	        'fas fa-hockey-puck' => 'hockey-puck',
	        'fas fa-home' => 'home',
	        'fab fa-hooli' => 'hooli',
	        'fas fa-hospital' => 'hospital',
	        'far fa-hospital' => 'hospital',
	        'fas fa-hospital-alt' => 'hospital-alt',
	        'fas fa-hospital-symbol' => 'hospital-symbol',
	        'fab fa-hotjar' => 'hotjar',
	        'fas fa-hourglass' => 'hourglass',
	        'far fa-hourglass' => 'hourglass',
	        'fas fa-hourglass-end' => 'hourglass-end',
	        'fas fa-hourglass-half' => 'hourglass-half',
	        'fas fa-hourglass-start' => 'hourglass-start',
	        'fab fa-houzz' => 'houzz',
	        'fab fa-html5' => 'html5',
	        'fab fa-hubspot' => 'hubspot',
	        'fas fa-i-cursor' => 'i-cursor',
	        'fas fa-id-badge' => 'id-badge',
	        'far fa-id-badge' => 'id-badge',
	        'fas fa-id-card' => 'id-card',
	        'far fa-id-card' => 'id-card',
	        'fas fa-id-card-alt' => 'id-card-alt',
	        'fas fa-image' => 'image',
	        'far fa-image' => 'image',
	        'fas fa-images' => 'images',
	        'far fa-images' => 'images',
	        'fab fa-imdb' => 'imdb',
	        'fas fa-inbox' => 'inbox',
	        'fas fa-indent' => 'indent',
	        'fas fa-industry' => 'industry',
	        'fas fa-info' => 'info',
	        'fas fa-info-circle' => 'info-circle',
	        'fab fa-instagram' => 'instagram',
	        'fab fa-internet-explorer' => 'internet-explorer',
	        'fab fa-ioxhost' => 'ioxhost',
	        'fas fa-italic' => 'italic',
	        'fab fa-itunes' => 'itunes',
	        'fab fa-itunes-note' => 'itunes-note',
	        'fab fa-java' => 'java',
	        'fab fa-jenkins' => 'jenkins',
	        'fab fa-joget' => 'joget',
	        'fab fa-joomla' => 'joomla',
	        'fab fa-js' => 'js',
	        'fab fa-js-square' => 'js-square',
	        'fab fa-jsfiddle' => 'jsfiddle',
	        'fas fa-key' => 'key',
	        'fas fa-keyboard' => 'keyboard',
	        'far fa-keyboard' => 'keyboard',
	        'fab fa-keycdn' => 'keycdn',
	        'fab fa-kickstarter' => 'kickstarter',
	        'fab fa-kickstarter-k' => 'kickstarter-k',
	        'fab fa-korvue' => 'korvue',
	        'fas fa-language' => 'language',
	        'fas fa-laptop' => 'laptop',
	        'fab fa-laravel' => 'laravel',
	        'fab fa-lastfm' => 'lastfm',
	        'fab fa-lastfm-square' => 'lastfm-square',
	        'fas fa-leaf' => 'leaf',
	        'fab fa-leanpub' => 'leanpub',
	        'fas fa-lemon' => 'lemon',
	        'far fa-lemon' => 'lemon',
	        'fab fa-less' => 'less',
	        'fas fa-level-down-alt' => 'level-down-alt',
	        'fas fa-level-up-alt' => 'level-up-alt',
	        'fas fa-life-ring' => 'life-ring',
	        'far fa-life-ring' => 'life-ring',
	        'fas fa-lightbulb' => 'lightbulb',
	        'far fa-lightbulb' => 'lightbulb',
	        'fab fa-line' => 'line',
	        'fas fa-link' => 'link',
	        'fab fa-linkedin' => 'linkedin',
	        'fab fa-linkedin-in' => 'linkedin-in',
	        'fab fa-linode' => 'linode',
	        'fab fa-linux' => 'linux',
	        'fas fa-lira-sign' => 'lira-sign',
	        'fas fa-list' => 'list',
	        'fas fa-list-alt' => 'list-alt',
	        'far fa-list-alt' => 'list-alt',
	        'fas fa-list-ol' => 'list-ol',
	        'fas fa-list-ul' => 'list-ul',
	        'fas fa-location-arrow' => 'location-arrow',
	        'fas fa-lock' => 'lock',
	        'fas fa-lock-open' => 'lock-open',
	        'fas fa-long-arrow-alt-down' => 'long-arrow-alt-down',
	        'fas fa-long-arrow-alt-left' => 'long-arrow-alt-left',
	        'fas fa-long-arrow-alt-right' => 'long-arrow-alt-right',
	        'fas fa-long-arrow-alt-up' => 'long-arrow-alt-up',
	        'fas fa-low-vision' => 'low-vision',
	        'fab fa-lyft' => 'lyft',
	        'fab fa-magento' => 'magento',
	        'fas fa-magic' => 'magic',
	        'fas fa-magnet' => 'magnet',
	        'fas fa-male' => 'male',
	        'fas fa-map' => 'map',
	        'far fa-map' => 'map',
	        'fas fa-map-marker' => 'map-marker',
	        'fas fa-map-marker-alt' => 'map-marker-alt',
	        'fas fa-map-pin' => 'map-pin',
	        'fas fa-map-signs' => 'map-signs',
	        'fas fa-mars' => 'mars',
	        'fas fa-mars-double' => 'mars-double',
	        'fas fa-mars-stroke' => 'mars-stroke',
	        'fas fa-mars-stroke-h' => 'mars-stroke-h',
	        'fas fa-mars-stroke-v' => 'mars-stroke-v',
	        'fab fa-maxcdn' => 'maxcdn',
	        'fab fa-medapps' => 'medapps',
	        'fab fa-medium' => 'medium',
	        'fab fa-medium-m' => 'medium-m',
	        'fas fa-medkit' => 'medkit',
	        'fab fa-medrt' => 'medrt',
	        'fab fa-meetup' => 'meetup',
	        'fas fa-meh' => 'meh',
	        'far fa-meh' => 'meh',
	        'fas fa-mercury' => 'mercury',
	        'fas fa-microchip' => 'microchip',
	        'fas fa-microphone' => 'microphone',
	        'fas fa-microphone-slash' => 'microphone-slash',
	        'fab fa-microsoft' => 'microsoft',
	        'fas fa-minus' => 'minus',
	        'fas fa-minus-circle' => 'minus-circle',
	        'fas fa-minus-square' => 'minus-square',
	        'far fa-minus-square' => 'minus-square',
	        'fab fa-mix' => 'mix',
	        'fab fa-mixcloud' => 'mixcloud',
	        'fab fa-mizuni' => 'mizuni',
	        'fas fa-mobile' => 'mobile',
	        'fas fa-mobile-alt' => 'mobile-alt',
	        'fab fa-modx' => 'modx',
	        'fab fa-monero' => 'monero',
	        'fas fa-money-bill-alt' => 'money-bill-alt',
	        'far fa-money-bill-alt' => 'money-bill-alt',
	        'fas fa-moon' => 'moon',
	        'far fa-moon' => 'moon',
	        'fas fa-motorcycle' => 'motorcycle',
	        'fas fa-mouse-pointer' => 'mouse-pointer',
	        'fas fa-music' => 'music',
	        'fab fa-napster' => 'napster',
	        'fas fa-neuter' => 'neuter',
	        'fas fa-newspaper' => 'newspaper',
	        'far fa-newspaper' => 'newspaper',
	        'fab fa-nintendo-switch' => 'nintendo-switch',
	        'fab fa-node' => 'node',
	        'fab fa-node-js' => 'node-js',
	        'fas fa-notes-medical' => 'notes-medical',
	        'fab fa-npm' => 'npm',
	        'fab fa-ns8' => 'ns8',
	        'fab fa-nutritionix' => 'nutritionix',
	        'fas fa-object-group' => 'object-group',
	        'far fa-object-group' => 'object-group',
	        'fas fa-object-ungroup' => 'object-ungroup',
	        'far fa-object-ungroup' => 'object-ungroup',
	        'fab fa-odnoklassniki' => 'odnoklassniki',
	        'fab fa-odnoklassniki-square' => 'odnoklassniki-square',
	        'fab fa-opencart' => 'opencart',
	        'fab fa-openid' => 'openid',
	        'fab fa-opera' => 'opera',
	        'fab fa-optin-monster' => 'optin-monster',
	        'fab fa-osi' => 'osi',
	        'fas fa-outdent' => 'outdent',
	        'fab fa-page4' => 'page4',
	        'fab fa-pagelines' => 'pagelines',
	        'fas fa-paint-brush' => 'paint-brush',
	        'fab fa-palfed' => 'palfed',
	        'fas fa-pallet' => 'pallet',
	        'fas fa-paper-plane' => 'paper-plane',
	        'far fa-paper-plane' => 'paper-plane',
	        'fas fa-paperclip' => 'paperclip',
	        'fas fa-parachute-box' => 'parachute-box',
	        'fas fa-paragraph' => 'paragraph',
	        'fas fa-paste' => 'paste',
	        'fab fa-patreon' => 'patreon',
	        'fas fa-pause' => 'pause',
	        'fas fa-pause-circle' => 'pause-circle',
	        'far fa-pause-circle' => 'pause-circle',
	        'fas fa-paw' => 'paw',
	        'fab fa-paypal' => 'paypal',
	        'fas fa-pen-square' => 'pen-square',
	        'fas fa-pencil-alt' => 'pencil-alt',
	        'fas fa-people-carry' => 'people-carry',
	        'fas fa-percent' => 'percent',
	        'fab fa-periscope' => 'periscope',
	        'fab fa-phabricator' => 'phabricator',
	        'fab fa-phoenix-framework' => 'phoenix-framework',
	        'fas fa-phone' => 'phone',
	        'fas fa-phone-slash' => 'phone-slash',
	        'fas fa-phone-square' => 'phone-square',
	        'fas fa-phone-volume' => 'phone-volume',
	        'fab fa-php' => 'php',
	        'fab fa-pied-piper' => 'pied-piper',
	        'fab fa-pied-piper-alt' => 'pied-piper-alt',
	        'fab fa-pied-piper-hat' => 'pied-piper-hat',
	        'fab fa-pied-piper-pp' => 'pied-piper-pp',
	        'fas fa-piggy-bank' => 'piggy-bank',
	        'fas fa-pills' => 'pills',
	        'fab fa-pinterest' => 'pinterest',
	        'fab fa-pinterest-p' => 'pinterest-p',
	        'fab fa-pinterest-square' => 'pinterest-square',
	        'fas fa-plane' => 'plane',
	        'fas fa-play' => 'play',
	        'fas fa-play-circle' => 'play-circle',
	        'far fa-play-circle' => 'play-circle',
	        'fab fa-playstation' => 'playstation',
	        'fas fa-plug' => 'plug',
	        'fas fa-plus' => 'plus',
	        'fas fa-plus-circle' => 'plus-circle',
	        'fas fa-plus-square' => 'plus-square',
	        'far fa-plus-square' => 'plus-square',
	        'fas fa-podcast' => 'podcast',
	        'fas fa-poo' => 'poo',
	        'fas fa-pound-sign' => 'pound-sign',
	        'fas fa-power-off' => 'power-off',
	        'fas fa-prescription-bottle' => 'prescription-bottle',
	        'fas fa-prescription-bottle-alt' => 'prescription-bottle-alt',
	        'fas fa-print' => 'print',
	        'fas fa-procedures' => 'procedures',
	        'fab fa-product-hunt' => 'product-hunt',
	        'fab fa-pushed' => 'pushed',
	        'fas fa-puzzle-piece' => 'puzzle-piece',
	        'fab fa-python' => 'python',
	        'fab fa-qq' => 'qq',
	        'fas fa-qrcode' => 'qrcode',
	        'fas fa-question' => 'question',
	        'fas fa-question-circle' => 'question-circle',
	        'far fa-question-circle' => 'question-circle',
	        'fas fa-quidditch' => 'quidditch',
	        'fab fa-quinscape' => 'quinscape',
	        'fab fa-quora' => 'quora',
	        'fas fa-quote-left' => 'quote-left',
	        'fas fa-quote-right' => 'quote-right',
	        'fas fa-random' => 'random',
	        'fab fa-ravelry' => 'ravelry',
	        'fab fa-react' => 'react',
	        'fab fa-readme' => 'readme',
	        'fab fa-rebel' => 'rebel',
	        'fas fa-recycle' => 'recycle',
	        'fab fa-red-river' => 'red-river',
	        'fab fa-reddit' => 'reddit',
	        'fab fa-reddit-alien' => 'reddit-alien',
	        'fab fa-reddit-square' => 'reddit-square',
	        'fas fa-redo' => 'redo',
	        'fas fa-redo-alt' => 'redo-alt',
	        'fas fa-registered' => 'registered',
	        'far fa-registered' => 'registered',
	        'fab fa-rendact' => 'rendact',
	        'fab fa-renren' => 'renren',
	        'fas fa-reply' => 'reply',
	        'fas fa-reply-all' => 'reply-all',
	        'fab fa-replyd' => 'replyd',
	        'fab fa-resolving' => 'resolving',
	        'fas fa-retweet' => 'retweet',
	        'fas fa-ribbon' => 'ribbon',
	        'fas fa-road' => 'road',
	        'fas fa-rocket' => 'rocket',
	        'fab fa-rocketchat' => 'rocketchat',
	        'fab fa-rockrms' => 'rockrms',
	        'fas fa-rss' => 'rss',
	        'fas fa-rss-square' => 'rss-square',
	        'fas fa-ruble-sign' => 'ruble-sign',
	        'fas fa-rupee-sign' => 'rupee-sign',
	        'fab fa-safari' => 'safari',
	        'fab fa-sass' => 'sass',
	        'fas fa-save' => 'save',
	        'far fa-save' => 'save',
	        'fab fa-schlix' => 'schlix',
	        'fab fa-scribd' => 'scribd',
	        'fas fa-search' => 'search',
	        'fas fa-search-minus' => 'search-minus',
	        'fas fa-search-plus' => 'search-plus',
	        'fab fa-searchengin' => 'searchengin',
	        'fas fa-seedling' => 'seedling',
	        'fab fa-sellcast' => 'sellcast',
	        'fab fa-sellsy' => 'sellsy',
	        'fas fa-server' => 'server',
	        'fab fa-servicestack' => 'servicestack',
	        'fas fa-share' => 'share',
	        'fas fa-share-alt' => 'share-alt',
	        'fas fa-share-alt-square' => 'share-alt-square',
	        'fas fa-share-square' => 'share-square',
	        'far fa-share-square' => 'share-square',
	        'fas fa-shekel-sign' => 'shekel-sign',
	        'fas fa-shield-alt' => 'shield-alt',
	        'fas fa-ship' => 'ship',
	        'fas fa-shipping-fast' => 'shipping-fast',
	        'fab fa-shirtsinbulk' => 'shirtsinbulk',
	        'fas fa-shopping-bag' => 'shopping-bag',
	        'fas fa-shopping-basket' => 'shopping-basket',
	        'fas fa-shopping-cart' => 'shopping-cart',
	        'fas fa-shower' => 'shower',
	        'fas fa-sign' => 'sign',
	        'fas fa-sign-in-alt' => 'sign-in-alt',
	        'fas fa-sign-language' => 'sign-language',
	        'fas fa-sign-out-alt' => 'sign-out-alt',
	        'fas fa-signal' => 'signal',
	        'fab fa-simplybuilt' => 'simplybuilt',
	        'fab fa-sistrix' => 'sistrix',
	        'fas fa-sitemap' => 'sitemap',
	        'fab fa-skyatlas' => 'skyatlas',
	        'fab fa-skype' => 'skype',
	        'fab fa-slack' => 'slack',
	        'fab fa-slack-hash' => 'slack-hash',
	        'fas fa-sliders-h' => 'sliders-h',
	        'fab fa-slideshare' => 'slideshare',
	        'fas fa-smile' => 'smile',
	        'far fa-smile' => 'smile',
	        'fas fa-smoking' => 'smoking',
	        'fab fa-snapchat' => 'snapchat',
	        'fab fa-snapchat-ghost' => 'snapchat-ghost',
	        'fab fa-snapchat-square' => 'snapchat-square',
	        'fas fa-snowflake' => 'snowflake',
	        'far fa-snowflake' => 'snowflake',
	        'fas fa-sort' => 'sort',
	        'fas fa-sort-alpha-down' => 'sort-alpha-down',
	        'fas fa-sort-alpha-up' => 'sort-alpha-up',
	        'fas fa-sort-amount-down' => 'sort-amount-down',
	        'fas fa-sort-amount-up' => 'sort-amount-up',
	        'fas fa-sort-down' => 'sort-down',
	        'fas fa-sort-numeric-down' => 'sort-numeric-down',
	        'fas fa-sort-numeric-up' => 'sort-numeric-up',
	        'fas fa-sort-up' => 'sort-up',
	        'fab fa-soundcloud' => 'soundcloud',
	        'fas fa-space-shuttle' => 'space-shuttle',
	        'fab fa-speakap' => 'speakap',
	        'fas fa-spinner' => 'spinner',
	        'fab fa-spotify' => 'spotify',
	        'fas fa-square' => 'square',
	        'far fa-square' => 'square',
	        'fas fa-square-full' => 'square-full',
	        'fab fa-stack-exchange' => 'stack-exchange',
	        'fab fa-stack-overflow' => 'stack-overflow',
	        'fas fa-star' => 'star',
	        'far fa-star' => 'star',
	        'fas fa-star-half' => 'star-half',
	        'far fa-star-half' => 'star-half',
	        'fab fa-staylinked' => 'staylinked',
	        'fab fa-steam' => 'steam',
	        'fab fa-steam-square' => 'steam-square',
	        'fab fa-steam-symbol' => 'steam-symbol',
	        'fas fa-step-backward' => 'step-backward',
	        'fas fa-step-forward' => 'step-forward',
	        'fas fa-stethoscope' => 'stethoscope',
	        'fab fa-sticker-mule' => 'sticker-mule',
	        'fas fa-sticky-note' => 'sticky-note',
	        'far fa-sticky-note' => 'sticky-note',
	        'fas fa-stop' => 'stop',
	        'fas fa-stop-circle' => 'stop-circle',
	        'far fa-stop-circle' => 'stop-circle',
	        'fas fa-stopwatch' => 'stopwatch',
	        'fab fa-strava' => 'strava',
	        'fas fa-street-view' => 'street-view',
	        'fas fa-strikethrough' => 'strikethrough',
	        'fab fa-stripe' => 'stripe',
	        'fab fa-stripe-s' => 'stripe-s',
	        'fab fa-studiovinari' => 'studiovinari',
	        'fab fa-stumbleupon' => 'stumbleupon',
	        'fab fa-stumbleupon-circle' => 'stumbleupon-circle',
	        'fas fa-subscript' => 'subscript',
	        'fas fa-subway' => 'subway',
	        'fas fa-suitcase' => 'suitcase',
	        'fas fa-sun' => 'sun',
	        'far fa-sun' => 'sun',
	        'fab fa-superpowers' => 'superpowers',
	        'fas fa-superscript' => 'superscript',
	        'fab fa-supple' => 'supple',
	        'fas fa-sync' => 'sync',
	        'fas fa-sync-alt' => 'sync-alt',
	        'fas fa-syringe' => 'syringe',
	        'fas fa-table' => 'table',
	        'fas fa-table-tennis' => 'table-tennis',
	        'fas fa-tablet' => 'tablet',
	        'fas fa-tablet-alt' => 'tablet-alt',
	        'fas fa-tablets' => 'tablets',
	        'fas fa-tachometer-alt' => 'tachometer-alt',
	        'fas fa-tag' => 'tag',
	        'fas fa-tags' => 'tags',
	        'fas fa-tape' => 'tape',
	        'fas fa-tasks' => 'tasks',
	        'fas fa-taxi' => 'taxi',
	        'fab fa-telegram' => 'telegram',
	        'fab fa-telegram-plane' => 'telegram-plane',
	        'fab fa-tencent-weibo' => 'tencent-weibo',
	        'fas fa-terminal' => 'terminal',
	        'fas fa-text-height' => 'text-height',
	        'fas fa-text-width' => 'text-width',
	        'fas fa-th' => 'th',
	        'fas fa-th-large' => 'th-large',
	        'fas fa-th-list' => 'th-list',
	        'fab fa-themeisle' => 'themeisle',
	        'fas fa-thermometer' => 'thermometer',
	        'fas fa-thermometer-empty' => 'thermometer-empty',
	        'fas fa-thermometer-full' => 'thermometer-full',
	        'fas fa-thermometer-half' => 'thermometer-half',
	        'fas fa-thermometer-quarter' => 'thermometer-quarter',
	        'fas fa-thermometer-three-quarters' => 'thermometer-three-quarters',
	        'fas fa-thumbs-down' => 'thumbs-down',
	        'far fa-thumbs-down' => 'thumbs-down',
	        'fas fa-thumbs-up' => 'thumbs-up',
	        'far fa-thumbs-up' => 'thumbs-up',
	        'fas fa-thumbtack' => 'thumbtack',
	        'fas fa-ticket-alt' => 'ticket-alt',
			'fas fa-times' => 'times',
	        'fab fa-tiktok' => 'tiktok',
	        'fas fa-times-circle' => 'times-circle',
	        'far fa-times-circle' => 'times-circle',
	        'fas fa-tint' => 'tint',
	        'fas fa-toggle-off' => 'toggle-off',
	        'fas fa-toggle-on' => 'toggle-on',
	        'fas fa-trademark' => 'trademark',
	        'fas fa-train' => 'train',
	        'fas fa-transgender' => 'transgender',
	        'fas fa-transgender-alt' => 'transgender-alt',
	        'fas fa-trash' => 'trash',
	        'fas fa-trash-alt' => 'trash-alt',
	        'far fa-trash-alt' => 'trash-alt',
	        'fas fa-tree' => 'tree',
	        'fab fa-trello' => 'trello',
	        'fab fa-tripadvisor' => 'tripadvisor',
	        'fas fa-trophy' => 'trophy',
	        'fas fa-truck' => 'truck',
	        'fas fa-truck-loading' => 'truck-loading',
	        'fas fa-truck-moving' => 'truck-moving',
	        'fas fa-tty' => 'tty',
	        'fab fa-tumblr' => 'tumblr',
	        'fab fa-tumblr-square' => 'tumblr-square',
	        'fas fa-tv' => 'tv',
	        'fab fa-twitch' => 'twitch',
	        'fab fa-twitter' => 'twitter',
	        'fab fa-twitter-square' => 'twitter-square',
	        'fab fa-typo3' => 'typo3',
	        'fab fa-uber' => 'uber',
	        'fab fa-uikit' => 'uikit',
	        'fas fa-umbrella' => 'umbrella',
	        'fas fa-underline' => 'underline',
	        'fas fa-undo' => 'undo',
	        'fas fa-undo-alt' => 'undo-alt',
	        'fab fa-uniregistry' => 'uniregistry',
	        'fas fa-universal-access' => 'universal-access',
	        'fas fa-university' => 'university',
	        'fas fa-unlink' => 'unlink',
	        'fas fa-unlock' => 'unlock',
	        'fas fa-unlock-alt' => 'unlock-alt',
	        'fab fa-untappd' => 'untappd',
	        'fas fa-upload' => 'upload',
	        'fab fa-usb' => 'usb',
	        'fas fa-user' => 'user',
	        'far fa-user' => 'user',
	        'fas fa-user-circle' => 'user-circle',
	        'far fa-user-circle' => 'user-circle',
	        'fas fa-user-md' => 'user-md',
	        'fas fa-user-plus' => 'user-plus',
	        'fas fa-user-secret' => 'user-secret',
	        'fas fa-user-times' => 'user-times',
	        'fas fa-users' => 'users',
	        'fab fa-ussunnah' => 'ussunnah',
	        'fas fa-utensil-spoon' => 'utensil-spoon',
	        'fas fa-utensils' => 'utensils',
	        'fab fa-vaadin' => 'vaadin',
	        'fas fa-venus' => 'venus',
	        'fas fa-venus-double' => 'venus-double',
	        'fas fa-venus-mars' => 'venus-mars',
	        'fab fa-viacoin' => 'viacoin',
	        'fab fa-viadeo' => 'viadeo',
	        'fab fa-viadeo-square' => 'viadeo-square',
	        'fas fa-vial' => 'vial',
	        'fas fa-vials' => 'vials',
	        'fab fa-viber' => 'viber',
	        'fas fa-video' => 'video',
	        'fas fa-video-slash' => 'video-slash',
	        'fab fa-vimeo' => 'vimeo',
	        'fab fa-vimeo-square' => 'vimeo-square',
	        'fab fa-vimeo-v' => 'vimeo-v',
	        'fab fa-vine' => 'vine',
	        'fab fa-vk' => 'vk',
	        'fab fa-vnv' => 'vnv',
	        'fas fa-volleyball-ball' => 'volleyball-ball',
	        'fas fa-volume-down' => 'volume-down',
	        'fas fa-volume-off' => 'volume-off',
	        'fas fa-volume-up' => 'volume-up',
	        'fab fa-vuejs' => 'vuejs',
	        'fas fa-warehouse' => 'warehouse',
	        'fab fa-weibo' => 'weibo',
	        'fas fa-weight' => 'weight',
	        'fab fa-weixin' => 'weixin',
	        'fab fa-whatsapp' => 'whatsapp',
	        'fab fa-whatsapp-square' => 'whatsapp-square',
	        'fas fa-wheelchair' => 'wheelchair',
	        'fab fa-whmcs' => 'whmcs',
	        'fas fa-wifi' => 'wifi',
	        'fab fa-wikipedia-w' => 'wikipedia-w',
	        'fas fa-window-close' => 'window-close',
	        'far fa-window-close' => 'window-close',
	        'fas fa-window-maximize' => 'window-maximize',
	        'far fa-window-maximize' => 'window-maximize',
	        'fas fa-window-minimize' => 'window-minimize',
	        'far fa-window-minimize' => 'window-minimize',
	        'fas fa-window-restore' => 'window-restore',
	        'far fa-window-restore' => 'window-restore',
	        'fab fa-windows' => 'windows',
	        'fas fa-wine-glass' => 'wine-glass',
	        'fas fa-won-sign' => 'won-sign',
	        'fab fa-wordpress' => 'wordpress',
	        'fab fa-wordpress-simple' => 'wordpress-simple',
	        'fab fa-wpbeginner' => 'wpbeginner',
	        'fab fa-wpexplorer' => 'wpexplorer',
	        'fab fa-wpforms' => 'wpforms',
	        'fas fa-wrench' => 'wrench',
	        'fas fa-x-ray' => 'x-ray',
	        'fab fa-xbox' => 'xbox',
	        'fab fa-xing' => 'xing',
	        'fab fa-xing-square' => 'xing-square',
	        'fab fa-y-combinator' => 'y-combinator',
	        'fab fa-yahoo' => 'yahoo',
	        'fab fa-yandex' => 'yandex',
	        'fab fa-yandex-international' => 'yandex-international',
	        'fab fa-yelp' => 'yelp',
	        'fas fa-yen-sign' => 'yen-sign',
	        'fab fa-yoast' => 'yoast',
	        'fab fa-youtube' => 'youtube',
	    ];
	}

	public function refresh($reload = true) {
		$this->load->language('marketplace/modification');

		$this->load->model('setting/modification');

		if ($this->mf__6262eb5c2a83c9f29edc0359ada36fe4()) {
			// Just before files are deleted, if config settings say maintenance mode is off then turn it on
			$maintenance = $this->config->get('config_maintenance');

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', true);

			//Log
			$log = [];

			// Clear all modification files
			$files = [];

			// Make path into an array
			$path = [DIR_MODIFICATION . '*'];

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}

			// Begin
			$xml = [];

			// Load the default modification XML
			$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

			// This is purly for developers so they can run mods directly and have them run without upload after each change.
			$files = glob(DIR_SYSTEM . '*.ocmod.xml');

			if ($files) {
				foreach ($files as $file) {
					$xml[] = file_get_contents($file);
				}
			}

			// Get the default modification file
			$results = $this->model_setting_modification->getModifications();

			foreach ($results as $result) {
				if ($result['status']) {
					$xml[] = $result['xml'];
				}
			}

			$modification = [];

			foreach ($xml as $xml) {
				if (empty($xml)){
					continue;
				}

				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);

				// Log
				$log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

				// Wipe the past modification store in the backup array
				$recovery = [];

				// Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
				if (isset($modification)) {
					$recovery = $modification;
				}

				$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

				foreach ($files as $file) {
					$operations = $file->getElementsByTagName('operation');

					$files = explode('|', $file->getAttribute('path'));

					foreach ($files as $file) {
						$path = '';

						// Get the full path of the files that are going to be used for modification
						if ((substr($file, 0, 7) == 'catalog')) {
							$path = DIR_CATALOG . substr($file, 8);
						}

						if ((substr($file, 0, 5) == 'admin')) {
							$path = DIR_APPLICATION . substr($file, 6);
						}

						if ((substr($file, 0, 6) == 'system')) {
							$path = DIR_SYSTEM . substr($file, 7);
						}

						if ($path) {
							$files = glob($path, GLOB_BRACE);

							if ($files) {
								foreach ($files as $file) {
									// Get the key to be used for the modification cache filename.
									if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
										$key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
									}

									if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
										$key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
									}

									if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
										$key = 'system/' . substr($file, strlen(DIR_SYSTEM));
									}

									// If file contents is not already in the modification array we need to load it.
									if (!isset($modification[$key])) {
										$content = file_get_contents($file);

										$modification[$key] = preg_replace('~\r?\n~', "\n", $content);
										$original[$key] = preg_replace('~\r?\n~', "\n", $content);

										// Log
										$log[] = PHP_EOL . 'FILE: ' . $key;
									}

									foreach ($operations as $operation) {
										$error = $operation->getAttribute('error');

										// Ignoreif
										$ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

										if ($ignoreif) {
											if ($ignoreif->getAttribute('regex') != 'true') {
												if (strpos($modification[$key], $ignoreif->textContent) !== false) {
													continue;
												}
											} else {
												if (preg_match($ignoreif->textContent, $modification[$key])) {
													continue;
												}
											}
										}

										$status = false;

										// Search and replace
										if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
											// Search
											$search = $operation->getElementsByTagName('search')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
											$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

											// Trim line if no trim attribute is set or is set to true.
											if (!$trim || $trim == 'true') {
												$search = trim($search);
											}

											// Add
											$add = $operation->getElementsByTagName('add')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
											$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
											$offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

											if ($offset == '') {
												$offset = 0;
											}

											// Trim line if is set to true.
											if ($trim == 'true') {
												$add = trim($add);
											}

											// Log
											$log[] = 'CODE: ' . $search;

											// Check if using indexes
											if ($index !== '') {
												$indexes = explode(',', $index);
											} else {
												$indexes = [];
											}

											// Get all the matches
											$i = 0;

											$lines = explode("\n", $modification[$key]);

											for ($line_id = 0; $line_id < count($lines); $line_id++) {
												$line = $lines[$line_id];

												// Status
												$match = false;

												// Check to see if the line matches the search code.
												if (stripos($line, $search) !== false) {
													// If indexes are not used then just set the found status to true.
													if (!$indexes) {
														$match = true;
													} elseif (in_array($i, $indexes)) {
														$match = true;
													}

													$i++;
												}

												// Now for replacing or adding to the matched elements
												if ($match) {
													switch ($position) {
														default:
														case 'replace':
															$new_lines = explode("\n", $add);

															if ($offset < 0) {
																array_splice($lines, $line_id + $offset, abs($offset) + 1, [str_replace($search, $add, $line)]);

																$line_id -= $offset;
															} else {
																array_splice($lines, $line_id, $offset + 1, [str_replace($search, $add, $line)]);
															}
															break;
														case 'before':
															$new_lines = explode("\n", $add);

															array_splice($lines, $line_id - $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
														case 'after':
															$new_lines = explode("\n", $add);

															array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
													}

													// Log
													$log[] = 'LINE: ' . $line_id;

													$status = true;
												}
											}

											$modification[$key] = implode("\n", $lines);
										} else {
											$search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
											$limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
											$replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

											// Limit
											if (!$limit) {
												$limit = -1;
											}

											// Log
											$match = [];

											preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

											// Remove part of the the result if a limit is set.
											if ($limit > 0) {
												$match[0] = array_slice($match[0], 0, $limit);
											}

											if ($match[0]) {
												$log[] = 'REGEX: ' . $search;

												for ($i = 0; $i < count($match[0]); $i++) {
													$log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
												}

												$status = true;
											}

											// Make the modification
											$modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
										}

										if (!$status) {
											// Abort applying this modification completely.
											if ($error == 'abort') {
												$modification = $recovery;
												// Log
												$log[] = 'NOT FOUND - ABORTING!';
												break 5;
											}
											// Skip current operation or break
											elseif ($error == 'skip') {
												// Log
												$log[] = 'NOT FOUND - OPERATION SKIPPED!';
												continue;
											}
											// Break current operations
											else {
												// Log
												$log[] = 'NOT FOUND - OPERATIONS ABORTED!';
											 	break;
											}
										}
									}
								}
							}
						}
					}
				}

				// Log
				$log[] = '----------------------------------------------------------------';
			}

			// Log
			$ocmod = new Log('ocmod.log');
			$ocmod->write(implode("\n", $log));

			// Write all modification files
			foreach ($modification as $key => $value) {
				// Only create a file if there are changes
				if ($original[$key] != $value) {
					$path = '';

					$directories = explode('/', dirname($key));

					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;

						if (!is_dir(DIR_MODIFICATION . $path)) {
							@mkdir(DIR_MODIFICATION . $path, 0777);
						}
					}

					$handle = fopen(DIR_MODIFICATION . $key, 'w');

					fwrite($handle, $value);

					fclose($handle);
				}
			}

			// Maintance mode back to original settings
			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);

			// Do not return success message if refresh() was called with $data
			$this->session->data['success'] = $this->language->get('text_success');

			$handle = fopen(DIR_LOGS . 'ocmod.log', 'w+');

			fclose($handle);

			$url = '';

			if (isset($this->request->get['store_id'])) {
				$url .= '&store_id=' . $this->request->get['store_id'];
			} else {
				$url .= '&store_id=0';
			}

			if ($reload) {
				$this->response->redirect($this->url->link('extension/theme/oct_feelmart', 'user_token=' . $this->session->data['user_token'] . '&type=theme' . $url, true));
			}
		}
	}

	public function update() {
		if (isset($this->request->get['store_id'])) {
			$url = '&store_id=' . $this->request->get['store_id'];
			$store_id = $this->request->get['store_id'];
		} else {
			$url = '&store_id=0';
			$store_id = 0;
		}

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('localisation/language');

		$stores = $this->model_setting_store->getStores();

		if ($stores) {
			$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_version', $this->version, 0);

			$upd_img = false;

			if ($this->config->get('theme_oct_feelmart_lazyload_image') && $this->config->get('theme_oct_feelmart_lazyload_image') == 'catalog/1lazy/oct_loader_product.gif') {
				$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_lazyload_image', 'catalog/1lazy/lazy-image.svg', 0);

				$upd_img = true;
			}

			foreach ($stores as $store) {
				$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_version', $this->version, $store['store_id']);

				if ($upd_img) {
					$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_lazyload_image', 'catalog/1lazy/lazy-image.svg', $store['store_id']);
				}
			}
		} else {
			$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_version', $this->version, $store_id);

			if ($this->config->get('theme_oct_feelmart_lazyload_image') && $this->config->get('theme_oct_feelmart_lazyload_image') == 'catalog/1lazy/oct_loader_product.gif') {
				$this->model_setting_setting->editSettingValue('theme_oct_feelmart', 'theme_oct_feelmart_lazyload_image', 'catalog/1lazy/lazy-image.svg', $store_id);
			}
		}

		if (!$this->user->hasPermission('access', 'octemplates/module/oct_information_bar')) {
			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'octemplates/module/oct_information_bar');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'octemplates/module/oct_information_bar');
		}

		$field_oct_image = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category` WHERE Field='oct_image'")->num_rows;

		if (!$field_oct_image) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `oct_image` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `image`;");
		}

		$field_reply = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "review` WHERE Field='reply'")->num_rows;

		if (!$field_reply) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "review` ADD `reply` text NOT NULL AFTER `text`;");
		}

		/* oct_shop_advantages START */
		if (!$this->user->hasPermission('access', 'extension/module/oct_benefits')) {
			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/oct_benefits');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/oct_benefits');
		}

		if (file_exists(DIR_APPLICATION.'controller/extension/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'controller/extension/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/en-gb/extension/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/en-gb/extension/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/ru-ru/extension/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/ru-ru/extension/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/uk-ua/extension/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/uk-ua/extension/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/en-gb/octemplates/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/en-gb/octemplates/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/ru-ru/octemplates/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/ru-ru/octemplates/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'language/uk-ua/octemplates/module/oct_shop_advantages.php')) {
			unlink(DIR_APPLICATION.'language/uk-ua/octemplates/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_APPLICATION.'view/template/octemplates/module/oct_shop_advantages.twig')) {
			unlink(DIR_APPLICATION.'view/template/octemplates/module/oct_shop_advantages.twig');
		}

		if (file_exists(DIR_CATALOG.'controller/extension/module/oct_shop_advantages.php')) {
			unlink(DIR_CATALOG.'controller/extension/module/oct_shop_advantages.php');
		}

		if (file_exists(DIR_CATALOG.'view/theme/oct_feelmart/template/octemplates/module/oct_shop_advantages.twig')) {
			unlink(DIR_CATALOG.'view/theme/oct_feelmart/template/octemplates/module/oct_shop_advantages.twig');
		}

		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."module WHERE code = 'oct_shop_advantages'");

		if ($query->num_rows) {
			foreach ($query->rows as $key => $benefit) {
				$benefits = [];

				$name = $benefit['name'];
				$module_id = $benefit['module_id'];

				$setting = (array)json_decode($benefit['setting'], true);

				$benefits['status'] = $setting['status'];
				$benefits['name'] = $setting['name'];

				$benefits['oct_benegits_data'][0] = [
					'icon' => $setting['tab_icon_block1'],
					'color_icon' => $setting['color_icon_block1'],
			        'color_fon_hover' => $setting['background_block_hover_block1'],
			        'color_title' => $setting['color_heading_block1'],
			        'color_text' => $setting['color_text_block1'],
				];

				foreach ($setting['heading_block1'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][0]['title'][$l_id['language_id']] = $value;
				}

				foreach ($setting['text_block1'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][0]['text'][$l_id['language_id']] = $value;
				}

				$q_seo = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = 'information_id=" . (int)$setting['select-heading_indormation_id_block1'] . "' AND store_id = '". $this->request->get['store_id'] ."'");

				foreach ($q_seo->rows as $value) {
					$benefits['oct_benegits_data'][0]['link'][$value['language_id']] = '/'.$value['keyword'].'/';
				}

				$benefits['oct_benegits_data'][1] = [
					'icon' => $setting['tab_icon_block2'],
					'color_icon' => $setting['color_icon_block2'],
			        'color_fon_hover' => $setting['background_block_hover_block2'],
			        'color_title' => $setting['color_heading_block2'],
			        'color_text' => $setting['color_text_block2'],
				];

				foreach ($setting['heading_block2'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][1]['title'][$l_id['language_id']] = $value;
				}

				foreach ($setting['text_block2'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][1]['text'][$l_id['language_id']] = $value;
				}

				$q_seo = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = 'information_id=" . (int)$setting['select-heading_indormation_id_block2'] . "' AND store_id = '". $this->request->get['store_id'] ."'");

				foreach ($q_seo->rows as $value) {
					$benefits['oct_benegits_data'][1]['link'][$value['language_id']] = '/'.$value['keyword'].'/';
				}

				$benefits['oct_benegits_data'][2] = [
					'icon' => $setting['tab_icon_block3'],
					'color_icon' => $setting['color_icon_block3'],
			        'color_fon_hover' => $setting['background_block_hover_block3'],
			        'color_title' => $setting['color_heading_block3'],
			        'color_text' => $setting['color_text_block3'],
				];

				foreach ($setting['heading_block3'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][2]['title'][$l_id['language_id']] = $value;
				}

				foreach ($setting['text_block3'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][2]['text'][$l_id['language_id']] = $value;
				}

				$q_seo = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = 'information_id=" . (int)$setting['select-heading_indormation_id_block3'] . "' AND store_id = '". $this->request->get['store_id'] ."'");

				foreach ($q_seo->rows as $value) {
					$benefits['oct_benegits_data'][2]['link'][$value['language_id']] = '/'.$value['keyword'].'/';
				}

				$benefits['oct_benegits_data'][3] = [
					'icon' => $setting['tab_icon_block4'],
					'color_icon' => $setting['color_icon_block4'],
			        'color_fon_hover' => $setting['background_block_hover_block4'],
			        'color_title' => $setting['color_heading_block4'],
			        'color_text' => $setting['color_text_block4'],
				];

				foreach ($setting['heading_block4'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][3]['title'][$l_id['language_id']] = $value;
				}

				foreach ($setting['text_block4'] as $key => $value) {
					$l_id = $this->model_localisation_language->getLanguageByCode($key);

					$benefits['oct_benegits_data'][3]['text'][$l_id['language_id']] = $value;
				}

				$q_seo = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = 'information_id=" . (int)$setting['select-heading_indormation_id_block4'] . "' AND store_id = '". $this->request->get['store_id'] ."'");

				foreach ($q_seo->rows as $value) {
					$benefits['oct_benegits_data'][3]['link'][$value['language_id']] = '/'.$value['keyword'].'/';
				}

				$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($name) . "', `code` = 'oct_benefits', `setting` = '" . $this->db->escape(json_encode($benefits)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
			}
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'oct_shop_advantages'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "extension` SET `code` = 'oct_benefits' WHERE `extension_id` = '" . (int)$query->row['extension_id'] . "'");
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '%oct_shop_advantages%'");

		if ($query->num_rows) {
			foreach ($query->rows as $layout) {
				$code = str_replace('oct_shop_advantages', 'oct_benefits', $layout['code']);
				$this->db->query("UPDATE `" . DB_PREFIX . "layout_module` SET `code` = '". $this->db->escape($code) ."' WHERE `layout_module_id` = '" . (int)$layout['layout_module_id'] . "'");
			}
		}
		/* oct_shop_advantages END */

		$this->refresh(false);
		$this->cacheDelete();

		$file = DIR_APPLICATION  . 'view/stylesheet/bootstrap.css';

		if (is_file($file) && is_file(DIR_APPLICATION . 'view/stylesheet/sass/_bootstrap.scss')) {
			unlink($file);
		}

		$files = glob(DIR_CATALOG  . 'view/theme/*/stylesheet/sass/_bootstrap.scss');

		foreach ($files as $file) {
			$file = substr($file, 0, -21) . '/bootstrap.css';

			if (is_file($file)) {
				file_put_contents($file, '');

				unlink($file);
			}
		}
	}

	public function octSettings() {
		return [
			'theme_oct_feelmart_status',
			'theme_oct_feelmart_version',
			'theme_oct_feelmart_license',
			'theme_oct_feelmart_scripts_in_footer',
			'theme_oct_feelmart_lazyload_desktop',
			'theme_oct_feelmart_lazyload_mobile',
			'theme_oct_feelmart_lazyload_tablet',
			'theme_oct_feelmart_lazyload_image',
			'theme_oct_feelmart_data_colors',
			'theme_oct_feelmart_css_code',
			'theme_oct_feelmart_js_code',
			'theme_oct_feelmart_webp',
			'theme_oct_feelmart_no_quantity_last',
			'theme_oct_feelmart_sort_data',
			'theme_oct_feelmart_data',
			'theme_oct_feelmart_data_osucsess',
			'theme_oct_feelmart_data_atributes',
			'theme_oct_feelmart_data_cat_atr_limit',
			'theme_oct_feelmart_data_model',
			'theme_oct_feelmart_popup_cart_status',
			'theme_oct_feelmart_isPopup',
			'theme_oct_feelmart_product_limit',
			'theme_oct_feelmart_product_description_length',
			'theme_oct_feelmart_image_category_width',
			'theme_oct_feelmart_image_category_height',
			'theme_oct_feelmart_image_sub_category_width',
			'theme_oct_feelmart_image_sub_category_height',
			'theme_oct_feelmart_image_thumb_width',
			'theme_oct_feelmart_image_thumb_height',
			'theme_oct_feelmart_image_popup_width',
			'theme_oct_feelmart_image_popup_height',
			'theme_oct_feelmart_image_product_width',
			'theme_oct_feelmart_image_product_height',
			'theme_oct_feelmart_image_manufacturer_width',
			'theme_oct_feelmart_image_manufacturer_height',
			'theme_oct_feelmart_image_additional_width',
			'theme_oct_feelmart_image_additional_height',
			'theme_oct_feelmart_image_related_width',
			'theme_oct_feelmart_image_related_height',
			'theme_oct_feelmart_image_compare_width',
			'theme_oct_feelmart_image_compare_height',
			'theme_oct_feelmart_image_wishlist_width',
			'theme_oct_feelmart_image_wishlist_height',
			'theme_oct_feelmart_image_cart_width',
			'theme_oct_feelmart_image_cart_height',
			'theme_oct_feelmart_image_location_width',
			'theme_oct_feelmart_image_location_height',
			'theme_oct_feelmart_alert_status',
			'theme_oct_feelmart_alert_data',
			'theme_oct_feelmart_live_search_status',
			'theme_oct_feelmart_live_search_data',
			'theme_oct_feelmart_feedback_status',
			'theme_oct_feelmart_feedback_data',
			'theme_oct_feelmart_seo_title_status',
			'theme_oct_feelmart_seo_title_data',
			'theme_oct_feelmart_seo_url_status',
			'theme_oct_feelmart_seo_url_data',
		];
	}

	public function install() {
		$this->load->model('setting/setting');
		$this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/theme/oct_feelmart');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/theme/oct_feelmart');

		$this->model_setting_setting->editSetting('theme_oct_feelmart', [
			'theme_oct_feelmart_status' => 0,
			'theme_oct_feelmart_version' => $this->version,
			'theme_oct_feelmart_license' => '',
			'theme_oct_feelmart_directory' => 'oct_feelmart',
			'theme_oct_feelmart_product_limit' => 24,
			'theme_oct_feelmart_product_description_length' => 250,
			'theme_oct_feelmart_image_category_width' => 80,
			'theme_oct_feelmart_image_category_height' => 80,
			'theme_oct_feelmart_image_sub_category_width' => 88,
			'theme_oct_feelmart_image_sub_category_height' => 88,
			'theme_oct_feelmart_image_thumb_width' => 700,
			'theme_oct_feelmart_image_thumb_height' => 500,
			'theme_oct_feelmart_image_popup_width' => 700,
			'theme_oct_feelmart_image_popup_height' => 500,
			'theme_oct_feelmart_image_product_width' => 228,
			'theme_oct_feelmart_image_product_height' => 228,
			'theme_oct_feelmart_image_manufacturer_width' => 90,
			'theme_oct_feelmart_image_manufacturer_height' => 80,
			'theme_oct_feelmart_image_additional_width' => 90,
			'theme_oct_feelmart_image_additional_height' => 90,
			'theme_oct_feelmart_image_related_width' => 228,
			'theme_oct_feelmart_image_related_height' => 228,
			'theme_oct_feelmart_image_compare_width' => 90,
			'theme_oct_feelmart_image_compare_height' => 90,
			'theme_oct_feelmart_image_wishlist_width' => 100,
			'theme_oct_feelmart_image_wishlist_height' => 100,
			'theme_oct_feelmart_image_cart_width' => 100,
			'theme_oct_feelmart_image_cart_height' => 100,
			'theme_oct_feelmart_image_location_width' => 50,
			'theme_oct_feelmart_image_location_height' => 50,
			'theme_oct_feelmart_scripts_in_footer' => 0,
			'theme_oct_feelmart_lazyload_desktop' => 1,
			'theme_oct_feelmart_lazyload_mobile' => 1,
			'theme_oct_feelmart_lazyload_tablet' => 1,
			'theme_oct_feelmart_lazyload_image' => 'catalog/1lazy/lazy-image.svg',
			'theme_oct_feelmart_css_code' => '',
			'theme_oct_feelmart_js_code' => '',
			'theme_oct_feelmart_webp' => 0,
			'theme_oct_feelmart_no_quantity_last' => 1,
			'theme_oct_feelmart_data_atributes' => 0,
			'theme_oct_feelmart_data_cat_atr_limit' => 5,
			'theme_oct_feelmart_data_model' => 0,
			'theme_oct_feelmart_data_colors' => [
				'main_color' => 'rgb(24, 164, 225)',
				'main_link_color' => 'rgb(24, 164, 225)',
				'fon_color' => 'rgb(242, 243, 245)',
				'top_fon_color' => 'rgb(255, 255, 255)',
				'top_link_color' => 'rgb(55, 57, 65)',
				'top_link_color_hover' => 'rgb(24, 164, 225)',
				'search_bg' => 'rgb(28, 100, 187)',
				'menu_fon_color' => 'rgb(3, 83, 180)',
				'menu_fon_cat_color' => 'rgb(28, 100, 187)',
				'menu_fon_cat_hover_color' => 'rgb(65, 132, 213)',
				'menu_fon_cat_text_color' => 'rgb(255, 255, 255)',
				'menu_fon_cat_elements_color' => 'rgb(255, 255, 255)',
				'menu_fon_cat_link_color' => 'rgb(55, 57, 65)',
				'footer_fon_color' => 'rgb(32, 35, 52)',
				'footer_text_color' => 'rgb(238, 242, 245)',
				'footer_link_color' => 'rgb(166, 166, 166)',
				'footer_link_hover_color' => 'rgb(24, 164, 225)',
				'modal_fon_title_color' => 'rgb(24, 164, 225)',
				'modal_text_title_color' => 'rgb(238, 242, 245)',
				'modal_fon_icon_color' => 'rgb(238, 242, 245)',
				'mobile_fon_top_color' => 'rgb(238, 242, 245)',
				'mobile_fon_icon_c_color' => 'rgb(3, 83, 180)',
				'category_module_fon_color' => 'rgb(251, 251, 251)',
				'category_module_link_color' => 'rgb(102, 102, 102)',
				'category_module_link_hover_color' => 'rgb(24, 164, 225)',
				'logo_width' => 0,
				'compare' => 1,
				'wishlist' => 1,
			],
			'theme_oct_feelmart_sort_data' => [
				'deff_sort' => 'p.sort_order-ASC',
				'sorts' => [
					'p.sort_order-ASC',
					'p.sort_order-DESC',
					'pd.name-ASC',
					'pd.name-DESC',
					'p.price-ASC',
					'p.price-DESC',
					'p.model-ASC',
					'p.model-DESC',
					'p.quantity-ASC',
					'p.quantity-DESC',
					'p.viewed-ASC',
					'p.viewed-DESC',
					'p.date_added-ASC',
					'p.date_added-DESC',
					'rating-ASC',
					'rating-DESC',
				],
			],
			'theme_oct_feelmart_data' => [
				'minify' => 1,
				'micro' => 1,
				'open_graph' => 0,
				'header_information_links' => [],
				'header_product_views' => 1,
				'footer_totop' => 1,
				'footer_subscribe' => 1,
				'footer_link_contact' => 1,
				'footer_link_return' => 1,
				'footer_link_sitemap' => 1,
				'footer_link_man' => 1,
				'footer_link_cert' => 1,
				'footer_link_partners' => 1,
				'footer_link_specials' => 1,
				'footer_information_links' => [],
				'footer_category_links' => [],
				'mobile_sidebar_position' => 'bottom',
				'mobile_information_links' => [],
				'mobile_menu' => [
					'time' => 1,
					'address' => 1,
					'languages' => 1,
					'currency' => 1,
					'phones' => 1,
					'email' => 1,
					'telegram' => 1,
					'viber' => 1,
					'skype' => 1,
					'whatsapp' => 1,
					'messenger' => 1,
				],
				'contact_address' => [],
				'contact_telephone' => $this->config->get('config_telephone'),
				'contact_open' => [],
				'contact_map' => '',
				'contact_email' => $this->config->get('config_email'),
				'contact_skype' => '',
				'contact_whatsapp' => $this->config->get('config_telephone'),
				'contact_viber' => $this->config->get('config_telephone'),
				'contact_telegram' => $this->config->get('config_telephone'),
				'contact_messenger' => '',
				'contact_paymants' => [],
				'contact_socials' => [],
				'man_logo' => 1,
				'contact_view_map' => 1,
				'contact_view_html' => [],
				'category_desc_position' => 'bottom',
				'category_desc_in_page' => 1,
				'category_view_subcats' => 1,
				'category_subcat_products' => 1,
				'category_product_desc' => 1,
				'category_cat_image' => 1,
				'category_page' => 1,
				'category_view_sort_oder' => 1,
				'category_view_quantity' => 0,
				'product_dop_tab' => 0,
				'product_dop_tab_title' => [],
				'product_dop_tab_text' => [],
				'product_js_button' => '',
				'product_model' => 1,
				'product_gallery' => 0,
				'product_zoom' => 0,
				'product_advantage' => 0,
				'product_advantages' => [],
				'socials' => [
					0 => [
						'icone' => 'fab fa-facebook-f',
						'link' => '',
						'title' => 'Facebook'
					],
					1 => [
						'icone' => 'fab fa-twitter',
						'link' => '',
						'title' => 'Twitter'
					],
					2 => [
						'icone' => 'fab fa-instagram',
						'link' => '',
						'title' => 'Instagram'
					],
					3 => [
						'icone' => 'fab fa-vk',
						'link' => '',
						'title' => 'VK'
					],
					4 => [
						'icone' => 'fab fa-odnoklassniki',
						'link' => '',
						'title' => 'Od'
					],
				],
				'payments' => [
					'sber' => 1,
					'privat24' => 1,
					'ym' => 1,
					'wm' => 0,
					'visa' => 1,
					'qw' => 0,
					'skrill' => 0,
					'interkassa' => 0,
					'lp' => 0,
					'pp' => 0,
					'robo' => 0,
					'mc' => 1,
					'maestro' => 0,
					'customers' => [],
				]
			],
			'theme_oct_feelmart_popup_cart_status' => 1,
			'theme_oct_feelmart_popup_cart_data' => [],
			'theme_oct_feelmart_alert_status' => 0,
			'theme_oct_feelmart_alert_data' => [
				'orders' => 0,
				'products' => 0,
				'oct_modules' => 0,
			],
			'theme_oct_feelmart_feedback_status' => 1,
			'theme_oct_feelmart_feedback_data' => [
				'feedback_messenger' => 1,
				'feedback_viber' => 1,
				'feedback_telegram' => 1,
				'feedback_skype' => 1,
				'feedback_whatsapp' => 1,
				'feedback_email' => 1,
				'feedback_callback' => 1,
			],
			'theme_oct_feelmart_live_search_status' => 1,
			'theme_oct_feelmart_live_search_data' => [
				'delay' => 500,
				'price' => 1,
				'model' => 1,
				'sku' => 1,
				'count_symbol' => 2
			],
			'theme_oct_feelmart_seo_url_status' => 0,
			'theme_oct_feelmart_seo_url_data' => [
				'lang_prefix' => [],
				'product' => '[name]-[model]-[lang_prefix]',
				'category' => '[name]-[lang_prefix]',
				'manufacturer' => '[name]-[lang_prefix]',
				'information' => '[name]-[lang_prefix]',
				'blog_category' => '[name]-[lang_prefix]',
				'blog_article' => '[name]-[lang_prefix]',
			],
			'theme_oct_feelmart_seo_title_status' => 0,
			'theme_oct_feelmart_seo_title_data' => [
				'product' => [
					'title_status' => 0,
					'title_empty' => 0,
					'title' => '',
					'description_status' => 0,
					'description_empty' => 0,
					'description' => ''
				],
				'category' => [
					'title_status' => 0,
					'title_empty' => 0,
					'title' => '',
					'description_status' => 0,
					'description_empty' => 0,
					'description' => ''
				],
				'manufacturer' => [
					'title_status' => 0,
					'title' => '',
					'description_status' => 0,
					'description' => ''
				]
			],
		]);

		$this->load->model('octemplates/design/oct_banner_plus');
        $this->load->model('octemplates/design/oct_slideshow_plus');

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		$field_page_group_links = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category` WHERE Field='page_group_links'")->num_rows;

		if (!$field_page_group_links) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `page_group_links` text NOT NULL AFTER `status`;");
		}

		$field_oct_image = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category` WHERE Field='oct_image'")->num_rows;

		if (!$field_oct_image) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `oct_image` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `image`;");
		}

		$field_reply = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "review` WHERE Field='reply'")->num_rows;

		if (!$field_reply) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "review` ADD `reply` text NOT NULL AFTER `text`;");
		}

		//$this->model_setting_setting->editSettingValue('developer', 'developer_sass', 1, $store_id);

		$this->model_octemplates_design_oct_banner_plus->createDBTables();
        $this->model_octemplates_design_oct_slideshow_plus->createDBTables();

		$this->addOctPermissions('blog', glob(DIR_APPLICATION . 'controller/octemplates/blog/*.php'));
		$this->addOctPermissions('design', glob(DIR_APPLICATION . 'controller/octemplates/design/*.php'));
		$this->addOctPermissions('module', glob(DIR_APPLICATION . 'controller/octemplates/module/*.php'));
		$this->addOctPermissions('stickers', glob(DIR_APPLICATION . 'controller/octemplates/stickers/*.php'));
	}

	private function octCopyFiles($from, $to, $rewrite = true) {
		if (is_dir($from)) {
			@mkdir($to);

			$octDir = dir($from);

			while (false !== ($entry = $octDir->read())) {
				if ($entry == "." || $entry == "..") continue;

				$this->octCopyFiles("$from/$entry", "$to/$entry", $rewrite);
			}

			$octDir->close();
		} else {
			if (!file_exists($to) || $rewrite) {
				copy($from, $to);
			}
		}
	}

	private function addOctPermissions($type = 'module', $module_files) {
		$this->load->model('user/user_group');

	    if ($module_files) {
			foreach ($module_files as $file) {
				$extension = basename($file, '.php');

				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'octemplates/'. $type .'/' . $extension);
				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'octemplates/'. $type .'/' . $extension);
			}
		}
    }

	public function uninstall() {
	    $this->load->model('setting/setting');
	    $this->load->model('user/user_group');

        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/theme/oct_feelmart');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/theme/oct_feelmart');

	    $this->removeOctPermissions('blog', glob(DIR_APPLICATION . 'controller/octemplates/blog/*.php'));
		$this->removeOctPermissions('design', glob(DIR_APPLICATION . 'controller/octemplates/design/*.php'));
		$this->removeOctPermissions('module', glob(DIR_APPLICATION . 'controller/octemplates/module/*.php'));
		$this->removeOctPermissions('stickers', glob(DIR_APPLICATION . 'controller/octemplates/stickers/*.php'));

	    $this->model_setting_setting->deleteSetting('theme_oct_feelmart');
    }

	private function removeOctPermissions($type = 'module', $module_files) {
		$this->load->model('user/user_group');

	    if ($module_files) {
			foreach ($module_files as $file) {
				$extension = basename($file, '.php');

				$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'octemplates/'. $type .'/' . $extension);
				$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'octemplates/'. $type .'/' . $extension);
			}
		}
	}
protected function _2124160232($i){$a=['dGhlbWVfb2N0X2ZlZWxtYXJ0X2xpY2Vuc2U=','SFRUUF9IT1NU','dGhlbWVfb2N0X2ZlZWxtYXJ0X2xpY2Vuc2U=','bGljZW5zZQ==','ZXJyb3JfbGljZW5zZQ==','dGhlbWVfb2N0X2ZlZWxtYXJ0X3Byb2R1Y3RfbGltaXQ=','cHJvZHVjdF9saW1pdA==','ZXJyb3JfbGltaXQ=','dGhlbWVfb2N0X2ZlZWxtYXJ0X3Byb2R1Y3RfZGVzY3JpcHRpb25fbGVuZ3Ro','cHJvZHVjdF9kZXNjcmlwdGlvbl9sZW5ndGg=','ZXJyb3JfbGltaXQ=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NhdGVnb3J5X3dpZHRo','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NhdGVnb3J5X2hlaWdodA==','aW1hZ2VfY2F0ZWdvcnk=','ZXJyb3JfaW1hZ2VfY2F0ZWdvcnk=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3N1Yl9jYXRlZ29yeV93aWR0aA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3N1Yl9jYXRlZ29yeV9oZWlnaHQ=','aW1hZ2Vfc3ViX2NhdGVnb3J5','ZXJyb3JfaW1hZ2Vfc3ViX2NhdGVnb3J5','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3RodW1iX3dpZHRo','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3RodW1iX2hlaWdodA==','aW1hZ2VfdGh1bWI=','ZXJyb3JfaW1hZ2VfdGh1bWI=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3BvcHVwX3dpZHRo','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3BvcHVwX2hlaWdodA==','aW1hZ2VfcG9wdXA=','ZXJyb3JfaW1hZ2VfcG9wdXA=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX21hbnVmYWN0dXJlcl93aWR0aA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX21hbnVmYWN0dXJlcl9oZWlnaHQ=','aW1hZ2VfbWFudWZhY3R1cmVy','ZXJyb3JfaW1hZ2VfbWFudWZhY3R1cmVy','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3Byb2R1Y3Rfd2lkdGg=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3Byb2R1Y3RfaGVpZ2h0','aW1hZ2VfcHJvZHVjdA==','ZXJyb3JfaW1hZ2VfcHJvZHVjdA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2FkZGl0aW9uYWxfd2lkdGg=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2FkZGl0aW9uYWxfaGVpZ2h0','aW1hZ2VfYWRkaXRpb25hbA==','ZXJyb3JfaW1hZ2VfYWRkaXRpb25hbA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3JlbGF0ZWRfd2lkdGg=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3JlbGF0ZWRfaGVpZ2h0','aW1hZ2VfcmVsYXRlZA==','ZXJyb3JfaW1hZ2VfcmVsYXRlZA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NvbXBhcmVfd2lkdGg=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NvbXBhcmVfaGVpZ2h0','aW1hZ2VfY29tcGFyZQ==','ZXJyb3JfaW1hZ2VfY29tcGFyZQ==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3dpc2hsaXN0X3dpZHRo','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX3dpc2hsaXN0X2hlaWdodA==','aW1hZ2Vfd2lzaGxpc3Q=','ZXJyb3JfaW1hZ2Vfd2lzaGxpc3Q=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NhcnRfd2lkdGg=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2NhcnRfaGVpZ2h0','aW1hZ2VfY2FydA==','ZXJyb3JfaW1hZ2VfY2FydA==','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2xvY2F0aW9uX3dpZHRo','dGhlbWVfb2N0X2ZlZWxtYXJ0X2ltYWdlX2xvY2F0aW9uX2hlaWdodA==','aW1hZ2VfbG9jYXRpb24=','ZXJyb3JfaW1hZ2VfbG9jYXRpb24=','eG4tLQ==','eG4tLS0t','Lyg/UDxkb21haW4+W2EtejAtOV1bYS16MC05XC1dezEsNjN9XC5bYS16XC5dezIsN30pJC9p','ZG9tYWlu','ZW50cnlfb2N0X2luX2NhcnRfdG8=','eG4tLQ==','eG4tLS0t','','','Lyg/UDxkb21haW4+W2EtejAtOV1bYS16MC05XC1dezEsNjN9XC5bYS16MC05XC5dezIsN30pJC9p','ZG9tYWlu','ZW50cnlfb2N0X2luX2NhcnRfdG8=','LQ=='];return base64_decode($a[$i]);}protected function mf__f9ab05454998236921a6b0e281fae632(){if(!$this->{$this->_1774318978(24)}->{$this->_1774318978(25)}($this->_1774318978(26),$this->_1774318978(22))){$this->{$this->_1774318978(29)}['warning']=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_1774318978(27));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(0)]or $this->mf__d9c626d0d93d95fec63074f2e946e04c($this->{$this->_1774318978(0)}->server[$this->_2124160232(1)])!= $this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(2)]){$this->{$this->_1774318978(29)}[$this->_2124160232(3)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(4));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(5)]){$this->{$this->_1774318978(29)}[$this->_2124160232(6)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(7));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(8)]){$this->{$this->_1774318978(29)}[$this->_2124160232(9)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(10));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(11)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(12)]){$this->{$this->_1774318978(29)}[$this->_2124160232(13)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(14));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(15)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(16)]){$this->{$this->_1774318978(29)}[$this->_2124160232(17)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(18));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(19)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(20)]){$this->{$this->_1774318978(29)}[$this->_2124160232(21)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(22));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(23)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(24)]){$this->{$this->_1774318978(29)}[$this->_2124160232(25)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(26));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(27)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(28)]){$this->{$this->_1774318978(29)}[$this->_2124160232(29)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(30));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(31)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(32)]){$this->{$this->_1774318978(29)}[$this->_2124160232(33)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(34));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(35)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(36)]){$this->{$this->_1774318978(29)}[$this->_2124160232(37)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(38));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(39)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(40)]){$this->{$this->_1774318978(29)}[$this->_2124160232(41)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(42));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(43)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(44)]){$this->{$this->_1774318978(29)}[$this->_2124160232(45)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(46));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(47)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(48)]){$this->{$this->_1774318978(29)}[$this->_2124160232(49)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(50));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(51)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(52)]){$this->{$this->_1774318978(29)}[$this->_2124160232(53)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(54));}if(!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(55)]||!$this->{$this->_1774318978(0)}->{$this->_1774318978(7)}[$this->_2124160232(56)]){$this->{$this->_1774318978(29)}[$this->_2124160232(57)]=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(58));}return!$this->{$this->_1774318978(29)};}protected function mf__56a589c102d55f2199048a0f1a73f058($_fm60169cd1c47b7a7a85ab44f884635e41,$_fme4a3f5f7a18b1ed0ee22a93864ad15d8){if(!$this->{$this->_1774318978(30)}[$this->_1774318978(31)][0]($_fme4a3f5f7a18b1ed0ee22a93864ad15d8))$_fme4a3f5f7a18b1ed0ee22a93864ad15d8=[$_fme4a3f5f7a18b1ed0ee22a93864ad15d8];foreach($_fme4a3f5f7a18b1ed0ee22a93864ad15d8 as $_fmd3fe9c10a808a54ea2a3dbd9e605b696){if(($_fm2a039ed8fdbf4ceaa9e79cdc3aecd1a2=$this->{$this->_1774318978(30)}[$this->_1774318978(31)][1]($_fm60169cd1c47b7a7a85ab44f884635e41,$_fmd3fe9c10a808a54ea2a3dbd9e605b696))!==false)return $_fm2a039ed8fdbf4ceaa9e79cdc3aecd1a2;}return false;}protected function mf__d9c626d0d93d95fec63074f2e946e04c($_fm8409eaa6ec0ce2ea307354b2e150f8c2){$_fm6629c5988eefcd88ea6f77a2ae672b96=$this->{$this->_1774318978(30)}[$this->_1774318978(31)][2]($_fm8409eaa6ec0ce2ea307354b2e150f8c2,PHP_URL_PATH);if($this->mf__56a589c102d55f2199048a0f1a73f058($_fm6629c5988eefcd88ea6f77a2ae672b96,[$this->_2124160232(59),$this->_2124160232(60)])=== false){if($this->{$this->_1774318978(30)}[$this->_1774318978(31)][3]($this->_2124160232(61),$_fm6629c5988eefcd88ea6f77a2ae672b96,$_fmca53e6c0538f536b092f4738d0baaaa1)){$_fm8409eaa6ec0ce2ea307354b2e150f8c2=$_fmca53e6c0538f536b092f4738d0baaaa1[$this->_2124160232(62)] .$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(63));}}else{$_fm6629c5988eefcd88ea6f77a2ae672b96=$this->{$this->_1774318978(30)}[$this->_1774318978(31)][4]([$this->_2124160232(64),$this->_2124160232(65)],[$this->_2124160232(66),$this->_2124160232(67)],$_fm6629c5988eefcd88ea6f77a2ae672b96);if($this->{$this->_1774318978(30)}[$this->_1774318978(31)][5]($this->_2124160232(68),$_fm6629c5988eefcd88ea6f77a2ae672b96,$_fmca53e6c0538f536b092f4738d0baaaa1)){$_fm8409eaa6ec0ce2ea307354b2e150f8c2=$_fmca53e6c0538f536b092f4738d0baaaa1[$this->_2124160232(69)] .$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_2124160232(70));}}$_fme4a3f5f7a18b1ed0ee22a93864ad15d8=round(0+2.5+2.5);$_fm679e9b9234e2062f809dbd3325d37fb6=$this->{$this->_1774318978(30)}[$this->_1774318978(31)][6]($this->{$this->_1774318978(30)}[$this->_1774318978(31)][7]($_fm8409eaa6ec0ce2ea307354b2e150f8c2),round(0),$_fme4a3f5f7a18b1ed0ee22a93864ad15d8);$_fma16d2280393ce6a2a5428a4a8d09e354=$_fme4a3f5f7a18b1ed0ee22a93864ad15d8;while($_fma16d2280393ce6a2a5428a4a8d09e354<$this->{$this->_1774318978(30)}[$this->_1774318978(31)][8]($this->{$this->_1774318978(30)}[$this->_1774318978(31)][9]($_fm8409eaa6ec0ce2ea307354b2e150f8c2))){$_fm679e9b9234e2062f809dbd3325d37fb6 .= $this->_2124160232(71);$_fm679e9b9234e2062f809dbd3325d37fb6 .= $this->{$this->_1774318978(30)}[$this->_1774318978(31)][10]($this->{$this->_1774318978(30)}[$this->_1774318978(31)][11]($_fm8409eaa6ec0ce2ea307354b2e150f8c2),$_fma16d2280393ce6a2a5428a4a8d09e354,$_fme4a3f5f7a18b1ed0ee22a93864ad15d8);$_fma16d2280393ce6a2a5428a4a8d09e354=$_fma16d2280393ce6a2a5428a4a8d09e354+$_fme4a3f5f7a18b1ed0ee22a93864ad15d8;}return $this->{$this->_1774318978(30)}[$this->_1774318978(31)][12]($_fm679e9b9234e2062f809dbd3325d37fb6);}protected function mf__6262eb5c2a83c9f29edc0359ada36fe4(){if(!$this->{$this->_1774318978(24)}->{$this->_1774318978(25)}($this->_1774318978(26),$this->_1774318978(22))){$this->{$this->_1774318978(29)}['warning']=$this->{$this->_1774318978(16)}->{$this->_1774318978(8)}($this->_1774318978(27));}return!$this->{$this->_1774318978(29)};}
protected function _1774318978($i){$a=['cmVxdWVzdA==','c2VydmVy','UkVRVUVTVF9NRVRIT0Q=','UE9TVA==','bW9kZWxfc2V0dGluZ19zZXR0aW5n','ZWRpdFNldHRpbmc=','dGhlbWVfb2N0X2ZlZWxtYXJ0','cG9zdA==','Z2V0','c3RvcmVfaWQ=',
'Z2VuZXJhdGVDc3M=','dGhlbWVfb2N0X2ZlZWxtYXJ0X2RhdGFfY29sb3Jz','dGhlbWVfb2N0X2ZlZWxtYXJ0X2Nzc19jb2Rl','c2Vzc2lvbg==','ZGF0YQ==','c3VjY2Vzcw==','bGFuZ3VhZ2U=','dGV4dF9zdWNjZXNz','cmVzcG9uc2U=','cmVkaXJlY3Q=','dXJs','bGluaw==','ZXh0ZW5zaW9uL3RoZW1lL29jdF9mZWVsbWFydA==','dXNlcl90b2tlbg==','dXNlcg==','aGFzUGVybWlzc2lvbg==','bW9kaWZ5','ZXJyb3JfcGVybWlzc2lvbg==','c2VydmVy','ZXJyb3I=','Z3JldHJldA==','XzE1ODAyMzg3NzBf'];return base64_decode($a[$i]);}
}
