<?php
class ControllerCommonLanguage extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

		$data['code'] = $this->session->data['language'];

		$this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');

            // Правка роутинга языков
            //if ($this->language->get('code') == 'ua') $data['redirect'] = $this->url->link('common/home').'ru/';

		} else {
			$url_data = $this->request->get;

			// Правка роутинга языкового переключателя для лендинга
            if (isset($this->request->get['_route_'])) {
                $route = $url_data['_route_'];

                /**
                 * Языковой роутинг
                 */
                $route_parts = explode("/", $route);
                $last_route = end($route_parts);

                if ($this->language->get('code') == 'ru') $code = 'uk-ua';
                if ($this->language->get('code') == 'ua') $code = 'ru-ru';

                // меняем фрагменты урла на переведенные
                foreach ($route_parts as &$part) {
                    $query = $this->db->query("
                        SELECT
                            su.keyword 
                        FROM 
                            `oc_seo_url` su 
                        WHERE 
                            `query`=(SELECT `query` FROM `oc_seo_url` WHERE `keyword`='".$part."') 
                        AND 
                            `language_id`=(SELECT `language_id` FROM `oc_language` WHERE `code`='".$code."')
                        LIMIT 1
                    ")->rows;

                    if ($query !== []) $part = $query[0]['keyword'];
                }

                $route = implode('/', $route_parts);
                //print_r($route);

                $data['redirect'] = $this->request->server['REQUEST_SCHEME'].'://'.$this->request->server['HTTP_HOST'].'/'.$route;

            } else {
                // Это было исходное поведение
                unset($url_data['_route_']);

                $route = $url_data['route'];

                unset($url_data['route']);

                $url = '';

                if ($url_data) {
                    $url = '&' . urldecode(http_build_query($url_data, '', '&'));
                }

                $data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
            }
		}

        if ($this->registry->has('oct_mobiledetect')) {
            if ($this->oct_mobiledetect->isMobile() || $this->oct_mobiledetect->isTablet()) {
                return $this->load->view('common/language_mobile', $data);
            } else {
                return $this->load->view('common/language', $data);
            }
        } else {
            return $this->load->view('common/language', $data);
        }
        //return $this->load->view('common/language', $data);

	}

	public function language() {
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];

            // Сообщаем, что язык изменен через языковой переключатель
			$this->session->data['from_language_switcher'] = 'yes';
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}