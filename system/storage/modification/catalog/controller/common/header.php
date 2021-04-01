<?php
class ControllerCommonHeader extends Controller {

			public function octBreadcrumbs($data) {
				$data['oct_feelmart_data'] = $this->config->get('theme_oct_feelmart_data');

				return $this->load->view('octemplates/module/oct_breadcrumbs', $data);
			}
			
	public function index() {

			$data['oct_popup_call_phone_status'] = $this->config->get('oct_popup_call_phone_status');
			

			$data['oct_feelmart_data'] = $oct_feelmart_data = $this->config->get('theme_oct_feelmart_data');

			$data['oct_lang_id'] = (int)$this->config->get('config_language_id');

			if (isset($data['oct_feelmart_data']['contact_open'][(int)$this->config->get('config_language_id')])){
				$oct_contact_opens = explode(PHP_EOL, $data['oct_feelmart_data']['contact_open'][(int)$this->config->get('config_language_id')]);

				foreach ($oct_contact_opens as $oct_contact_open) {
					if (!empty($oct_contact_open)) {
						$data['oct_contact_opens'][] = html_entity_decode($oct_contact_open, ENT_QUOTES, 'UTF-8');
					}
				}
			}

			$oct_contact_telephones = explode(PHP_EOL, $data['oct_feelmart_data']['contact_telephone']);

			foreach ($oct_contact_telephones as $oct_contact_telephone) {
				if (!empty($oct_contact_telephone)) {
					$data['oct_contact_telephones'][] = html_entity_decode($oct_contact_telephone, ENT_QUOTES, 'UTF-8');
				}
			}

			if (isset($oct_feelmart_data['contact_address'])) {
				foreach ($oct_feelmart_data['contact_address'] as $oct_lang_id => $oct_adress) {
					$data['oct_feelmart_data']['contact_address'][$oct_lang_id] = html_entity_decode($oct_adress, ENT_QUOTES, 'UTF-8');
				}
			}

			if (isset($oct_feelmart_data['contact_map']) && !empty($oct_feelmart_data['contact_map'])) {
				$data['contact_map'] = html_entity_decode($oct_feelmart_data['contact_map'], ENT_QUOTES, 'UTF-8');
			}

			if ((isset($this->request->get['route']) && $this->request->get['route'] == 'common/home') || $this->request->server['REQUEST_URI'] == '/') {
				$data['oct_home'] = true;
			}

			$data['header_informations'] = [];

			if (isset($oct_feelmart_data['header_links']) && !empty($oct_feelmart_data['header_links'])) {
				//$site_link = $this->request->server['HTTPS'] ? $this->config->get('config_ssl') : $this->config->get('config_url');

				foreach ($oct_feelmart_data['header_links'] as $header_link) {
					$data['header_informations'][] = array(
						'title' => html_entity_decode($header_link[(int)$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8'),
						'href'  => $header_link[(int)$this->config->get('config_language_id')]['link']
					);
				}
			}

			$data['oct_sidebar_mobile'] = $this->load->controller('octemplates/module/oct_megamenu/mobileSideBar', 1);
			$data['sidebar_position'] = (isset($oct_feelmart_data['mobile_sidebar_position']) && !empty($oct_feelmart_data['mobile_sidebar_position'])) ? $oct_feelmart_data['mobile_sidebar_position'] : 'bottom';

			$data['wishlist_link'] = $this->url->link('account/wishlist','', true);

			if ($this->customer->isLogged()) {
				$this->load->model('account/wishlist');

				$data['wishlist_total'] = $this->model_account_wishlist->getTotalWishlist();
			} else {
				$data['wishlist_total'] = (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0);
			}

			$data['compare_link'] = $this->url->link('product/compare','', true);
			$data['compare_total'] = (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0);

			if (isset($oct_feelmart_data['header_product_views']) && $oct_feelmart_data['header_product_views']) {
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
			}
			
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {

            if (!$this->config->get('analytics_' . $analytic['code'] . '_position')) {
			
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));

            }
			
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}


			if ($this->config->get('analytics_oct_analytics_google_status') && $this->config->get('analytics_oct_analytics_google_webmaster_code')) {
				$data['oct_analytics_google_webmaster_code'] = html_entity_decode($this->config->get('analytics_oct_analytics_google_webmaster_code'), ENT_QUOTES, 'UTF-8');
			}

			if ($this->config->get('analytics_oct_analytics_yandex_status') && $this->config->get('analytics_oct_analytics_yandex_webmaster_code')) {
				$data['oct_analytics_yandex_webmaster_code'] = html_entity_decode($this->config->get('analytics_oct_analytics_yandex_webmaster_code'), ENT_QUOTES, 'UTF-8');
			}
			
		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		
			$this->load->model('octemplates/widgets/oct_minify');

			$main_css = $this->config->get('developer_sass') ? 'main' : 'main';

			$this->document->addOctStyle('catalog/view/theme/oct_feelmart/stylesheet/'. $main_css .'.css');

			if (file_exists(DIR_TEMPLATE.'oct_feelmart/stylesheet/dynamic_stylesheet_'. (int)$this->config->get('config_store_id') .'.css')) {
				$file_size = filesize(DIR_TEMPLATE.'oct_feelmart/stylesheet/dynamic_stylesheet_'. (int)$this->config->get('config_store_id') .'.css');

				if ($file_size) {
					$this->document->addOctStyle('catalog/view/theme/oct_feelmart/stylesheet/dynamic_stylesheet_'. (int)$this->config->get('config_store_id') .'.css');
				}
			}

			$data['styles'] = $this->model_octemplates_widgets_oct_minify->octMinifyCss($this->document->getOctStyles());
			
		$data['scripts'] = $this->document->getScripts('header');

			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/jquery-3.3.1.min.js');
			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/popper.min.js');
			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/bootstrap.min.js');
			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/main.js');
			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/bootstrap-notify/bootstrap-notify.js');
			//$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/lozad.js');
			$this->document->addOctScript('catalog/view/theme/oct_feelmart/js/common.js');

			$data['scripts'] = $this->model_octemplates_widgets_oct_minify->octMinifyJs($this->document->getOctScripts());
			
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

            $data['octOpenGraphs'] = (isset($oct_feelmart_data['open_graph']) && $oct_feelmart_data['open_graph']) ? $this->document->getOCTOpenGraph() : [];
			

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

			$data['oct_popup_cart_status'] = $this->config->get('theme_oct_feelmart_popup_cart_status');
			

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		
			if ($this->config->get('oct_megamenu_status')) {
				$data['menu'] = $this->load->controller('octemplates/module/oct_megamenu');
			} else {
				$data['menu'] = $this->load->controller('common/menu', ['deff' => 1]);
			}
			

		return $this->load->view('common/header', $data);
	}
}
