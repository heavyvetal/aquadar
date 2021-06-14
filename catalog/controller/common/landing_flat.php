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

		$this->load->language('common/landing_flat');

        $data['link_solution1'] = $this->language->get('link_solution1');
        $data['link_solution2'] = $this->language->get('link_solution2');
        $data['link_solution3'] = $this->language->get('link_solution3');
        $data['link_solution4'] = $this->language->get('link_solution4');

        if ($this->language->get('code') == 'ua') {
            $this->response->setOutput($this->load->view('common/landing_flat_ua', $data));
        } else {
            $this->response->setOutput($this->load->view('common/landing_flat_ru', $data));
        }
	}
}
