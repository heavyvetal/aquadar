<?php
class ControllerCommonLandingFlat extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        // В КВАРТИРЕ
        $information_id = 11;
        $this->load->model('catalog/information');
        $information_info = $this->model_catalog_information->getInformation($information_id);
        $data['content'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

		//$this->response->setOutput($this->load->view('common/landing_flat', $data));
        if ($this->language->get('code') == 'ua') {
            $this->response->setOutput($this->load->view('common/landing_flat_ua', $data));
        } else {
            $this->response->setOutput($this->load->view('common/landing_flat_ru', $data));
        }
	}
}
